<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $groups = [
            'general' => __('General Settings'),
            'appearance' => __('Appearance'),
            'email' => __('Email Settings'),
            'payment' => __('Payment Settings'),
            'seo' => __('SEO Settings'),
            'social' => __('Social Media'),
            'api' => __('API Settings'),
            'security' => __('Security Settings'),
        ];

        // Get all settings and group them by the 'group' column
        $allSettings = Setting::all();
        $settings = collect();
        
        foreach ($groups as $groupKey => $groupLabel) {
            $settings[$groupKey] = $allSettings->where('group', $groupKey)->values();
        }

        return view('admin.settings.index', compact('groups', 'settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            // Find the existing setting to preserve its group
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // Update only the value, preserve group and other attributes
                $setting->update(['value' => $value]);
            } else {
                // Create new setting if it doesn't exist
                Setting::set($key, $value);
            }
        }

        $activeTab = $request->input('active_tab', 'general');
        return redirect()->route('admin.settings.index')->with('success', __('Settings updated successfully'))->with('activeTab', $activeTab);
    }

    public function getByGroup($group)
    {
        $settings = Setting::getByGroup($group);
        return response()->json($settings);
    }
}
