<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
        'description',
        'type',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting?->value ?? $default;
    }

    public static function set($key, $value, $group = 'general', $label = null, $type = 'text')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'label' => $label ?? ucfirst(str_replace('_', ' ', $key)),
                'type' => $type,
            ]
        );
    }

    public static function getByGroup($group)
    {
        return static::where('group', $group)->get();
    }

    public static function getAllGrouped()
    {
        return static::all()->groupBy('group');
    }
}
