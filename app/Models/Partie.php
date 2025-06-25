<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Partie extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'contact_person',
        'gstin',
        'pan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope for active parties
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Scope for search
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('gstin', 'like', "%{$search}%")
                  ->orWhere('pan', 'like', "%{$search}%");
        });
    }

    // Get formatted phone number
    public function getFormattedPhoneAttribute(): string
    {
        return $this->phone;
    }

    // Get short GST number for display
    public function getShortGstinAttribute(): string
    {
        return $this->gstin ? substr($this->gstin, 0, 15) . (strlen($this->gstin) > 15 ? '...' : '') : '-';
    }

    // Get short PAN for display
    public function getShortPanAttribute(): string
    {
        return $this->pan ?? '-';
    }
}
