<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'role', 'url', 'thumbnail', 'alt_text', 'order'
    ];

    public function mediable()
    {
        return $this->morphTo();
    }

    // =================
    // Attribute Getters
    // =================

    public function getUrlAttribute($value)
    {
        // Fix: Prevent double 'storage/' in URL if $value already starts with 'storage/'
        if ($value && (str_starts_with($value, 'http://') || str_starts_with($value, 'https://'))) {
            return $value;
        }
        if ($value) {
            // Remove leading path slashes but do NOT double-prepend 'storage/'
            $cleanPath = ltrim($value, '/');
            if (str_starts_with($cleanPath, 'storage/')) {
                // Already stored as 'storage/...', just use asset() as is
                return asset($cleanPath);
            }
            return asset('storage/' . $cleanPath);
        }
        return null;
    }

    public function getThumbnailUrlAttribute()
    {
        $thumb = $this->attributes['thumbnail'] ?? null;
        if ($thumb && (str_starts_with($thumb, 'http://') || str_starts_with($thumb, 'https://'))) {
            return $thumb;
        }
        if ($thumb) {
            $cleanThumb = ltrim($thumb, '/');
            if (str_starts_with($cleanThumb, 'storage/')) {
                return asset($cleanThumb);
            }
            return asset('storage/' . $cleanThumb);
        }
        return null;
    }

    // ===============
    // Query Scopes
    // ===============

    /**
     * Scope for main role file(s)
     */
    public function scopeMain($query)
    {
        return $query->where('role', 'main');
    }

    /**
     * Scope for sub (supplementary) files
     */
    public function scopeSub($query)
    {
        return $query->where('role', 'sub');
    }

    /**
     * Convenience accessor: is this a main file?
     */
    public function getIsMainAttribute()
    {
        return $this->role === 'main';
    }

    /**
     * Convenience accessor: is this a sub file?
     */
    public function getIsSubAttribute()
    {
        return $this->role === 'sub';
    }
}
