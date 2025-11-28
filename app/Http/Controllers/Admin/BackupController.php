<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Category;
use App\Models\Country;
use App\Models\City;
use App\Models\Landmark;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Show backup management page
     */
    public function index()
    {
        $backups = [];
        $backupPath = storage_path('backups');
        
        if (File::isDirectory($backupPath)) {
            $files = File::files($backupPath);
            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'date' => $file->getBasename('.' . $file->getExtension()),
                    'created_at' => Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
                ];
            }
            usort($backups, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        return view('admin.backup.index', compact('backups'));
    }

    /**
     * Export system backup
     */
    public function export()
    {
        try {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $backupDir = storage_path("backups/{$timestamp}");
            File::makeDirectory($backupDir, 0755, true, true);

            // Export database tables
            $this->exportDatabaseTables($backupDir);

            // Export uploaded files
            $this->exportUploadedFiles($backupDir);

            // Create ZIP archive
            $zipPath = storage_path("backups/backup_{$timestamp}.zip");
            $this->createZipArchive($backupDir, $zipPath);

            // Clean up temporary directory
            File::deleteDirectory($backupDir);

            return response()->download($zipPath, "backup_{$timestamp}.zip")->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Backup export failed: ' . $e->getMessage());
        }
    }

    /**
     * Import system backup
     */
    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:104857600', // 100MB
        ]);

        try {
            $file = $request->file('backup_file');
            $extractPath = storage_path('backups/import_' . time());
            File::makeDirectory($extractPath, 0755, true, true);

            // Extract ZIP
            $zip = new ZipArchive();
            if ($zip->open($file->getPathname()) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                throw new \Exception('Failed to extract backup file');
            }

            // Import database tables
            $this->importDatabaseTables($extractPath);

            // Import uploaded files
            $this->importUploadedFiles($extractPath);

            // Clean up
            File::deleteDirectory($extractPath);

            return back()->with('success', 'Backup imported successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup import failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete backup file
     */
    public function delete($filename)
    {
        try {
            $backupPath = storage_path("backups/{$filename}");
            if (File::exists($backupPath)) {
                File::delete($backupPath);
                return back()->with('success', 'Backup deleted successfully');
            }
            return back()->with('error', 'Backup file not found');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Export database tables
     */
    private function exportDatabaseTables($backupDir)
    {
        // Get raw data to preserve JSON format
        $database = [
            'settings' => DB::table('settings')->get()->map(function($item) {
                if (is_string($item->options)) {
                    $item->options = json_decode($item->options, true);
                }
                return $item;
            })->toArray(),
            'products' => Product::all()->toArray(),
            'categories' => Category::all()->toArray(),
            'category_product' => DB::table('category_product')->get()->toArray(),
            'countries' => Country::all()->toArray(),
            'cities' => City::all()->toArray(),
            'landmarks' => Landmark::all()->toArray(),
            'orders' => Order::all()->toArray(),
            'users' => User::all()->toArray(),
        ];

        File::put(
            "{$backupDir}/database.json",
            json_encode($database, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Import database tables
     */
    private function importDatabaseTables($extractPath)
    {
        $databaseFile = "{$extractPath}/database.json";
        
        if (!File::exists($databaseFile)) {
            throw new \Exception('Database backup file not found');
        }

        $data = json_decode(File::get($databaseFile), true);

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Clear existing data (order matters due to foreign keys)
            DB::table('category_product')->truncate();
            DB::table('orders')->truncate();
            DB::table('landmarks')->truncate();
            DB::table('cities')->truncate();
            DB::table('countries')->truncate();
            DB::table('products')->truncate();
            DB::table('categories')->truncate();
            DB::table('settings')->truncate();

            // JSON fields by table
            $jsonFields = [
                'products' => ['colors', 'sizes', 'options'],
                'settings' => ['options'],
                'categories' => [],
                'countries' => [],
                'cities' => [],
                'landmarks' => ['timeline'],
                'orders' => ['items'],
            ];

            // Import data (reverse order of truncation)
            if (!empty($data['settings'])) {
                foreach ($data['settings'] as $record) {
                    $this->normalizeRecord($record, $jsonFields['settings'] ?? []);
                    DB::table('settings')->insert($record);
                }
            }
            if (!empty($data['categories'])) {
                foreach ($data['categories'] as $record) {
                    $this->normalizeRecord($record, $jsonFields['categories'] ?? []);
                    DB::table('categories')->insert($record);
                }
            }
            if (!empty($data['products'])) {
                foreach ($data['products'] as $record) {
                    $this->normalizeRecord($record, $jsonFields['products'] ?? []);
                    DB::table('products')->insert($record);
                }
            }
            if (!empty($data['category_product'])) {
                DB::table('category_product')->insert($data['category_product']);
            }
            if (!empty($data['countries'])) {
                foreach ($data['countries'] as $record) {
                    $this->normalizeRecord($record, $jsonFields['countries'] ?? []);
                    DB::table('countries')->insert($record);
                }
            }
            if (!empty($data['cities'])) {
                foreach ($data['cities'] as $record) {
                    $this->normalizeRecord($record, $jsonFields['cities'] ?? []);
                    DB::table('cities')->insert($record);
                }
            }
            if (!empty($data['landmarks'])) {
                foreach ($data['landmarks'] as $record) {
                    $this->normalizeRecord($record, $jsonFields['landmarks'] ?? []);
                    DB::table('landmarks')->insert($record);
                }
            }
            if (!empty($data['orders'])) {
                foreach ($data['orders'] as $record) {
                    $this->normalizeRecord($record, $jsonFields['orders'] ?? []);
                    DB::table('orders')->insert($record);
                }
            }
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Normalize a database record for insertion
     */
    private function normalizeRecord(&$record, $jsonFields = [])
    {
        // Convert datetime formats
        foreach (['created_at', 'updated_at', 'deleted_at', 'order_date'] as $dateField) {
            if (isset($record[$dateField])) {
                $record[$dateField] = $this->convertDatetime($record[$dateField]);
            }
        }

        // Handle JSON fields
        foreach ($jsonFields as $field) {
            if (isset($record[$field])) {
                if (is_array($record[$field])) {
                    $record[$field] = json_encode($record[$field]);
                } elseif (is_string($record[$field]) && !$this->isValidJson($record[$field])) {
                    // If it's a string that looks like it should be JSON but isn't, try to encode it
                    $record[$field] = json_encode($record[$field]);
                }
            }
        }
    }

    /**
     * Check if a string is valid JSON
     */
    private function isValidJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Convert ISO datetime to MySQL format
     */
    private function convertDatetime($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // If already in MySQL format, return as-is
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }
        
        // Convert ISO 8601 format to MySQL format
        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Export uploaded files
     */
    private function exportUploadedFiles($backupDir)
    {
        $uploadPath = storage_path('app/public');
        if (File::isDirectory($uploadPath)) {
            try {
                File::copyDirectory($uploadPath, "{$backupDir}/uploads");
            } catch (\Exception $e) {
                // Log but don't fail backup if file export fails
                Log::warning('Failed to export uploaded files: ' . $e->getMessage());
            }
        }
    }

    /**
     * Import uploaded files
     */
    private function importUploadedFiles($extractPath)
    {
        $uploadsPath = "{$extractPath}/uploads";
        if (File::isDirectory($uploadsPath)) {
            try {
                $storageUploadPath = storage_path('app/public');
                File::makeDirectory($storageUploadPath, 0755, true, true);
                
                // Copy entire directory recursively
                File::copyDirectory($uploadsPath, $storageUploadPath, true);
            } catch (\Exception $e) {
                // Log but don't fail import if file import fails
                Log::warning('Failed to import uploaded files: ' . $e->getMessage());
            }
        }
    }

    /**
     * Create ZIP archive from directory
     */
    private function createZipArchive($sourceDir, $zipPath)
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Failed to create ZIP archive');
        }

        $files = File::allFiles($sourceDir);
        foreach ($files as $file) {
            $relativePath = $file->getRelativePath() ? $file->getRelativePath() . DIRECTORY_SEPARATOR : '';
            $zip->addFile(
                $file->getPathname(),
                $relativePath . $file->getBasename()
            );
        }

        $zip->close();
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
