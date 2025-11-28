<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class SetMailPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:set-password {password? : The SMTP password or App Password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the SMTP mail password in settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = $this->argument('password');
        
        if (!$password) {
            $password = $this->secret('Enter SMTP password or App Password (for Gmail)');
        }
        
        if (empty($password)) {
            $this->error('Password cannot be empty!');
            return 1;
        }
        
        Setting::where('key', 'mail_password')->update(['value' => $password]);
        
        $this->info('Mail password updated successfully!');
        $this->line('');
        $this->line('Current mail settings:');
        
        $settings = Setting::whereIn('key', [
            'mail_mailer', 
            'mail_host', 
            'mail_port', 
            'mail_username', 
            'mail_encryption'
        ])->get();
        
        foreach ($settings as $setting) {
            $this->line("  {$setting->key}: {$setting->value}");
        }
        
        $this->line('  mail_password: ********');
        $this->line('');
        $this->warn('Remember to run: php artisan config:clear');
        
        return 0;
    }
}

