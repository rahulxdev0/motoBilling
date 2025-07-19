<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'phone',
        'email',
        'website',
        'gstin',
        'pan',
        'logo',
        'currency',
        'currency_symbol',
        'tax_percentage',
        'terms_conditions',
        'is_active',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active company
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }
}
