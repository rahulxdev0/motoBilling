<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'partie_id',
        'invoice_date',
        'invoice_type',
        'invoice_category',
        'due_date',
        'subtotal',
        'discount_percentage',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'round_off',
        'total',
        'paid_amount',
        'balance_amount',
        'payment_status',
        'status',
        'payment_terms',
        'terms_conditions',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'round_off' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    public function partie(): BelongsTo
    {
        return $this->belongsTo(Partie::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Check if this invoice is a cash sale
     */
    public function isCashSale()
    {
        // Assuming cash sale customer has a specific identifier or payment_status is 'paid'
        // and invoice_date = due_date
        return $this->payment_status === 'paid' && 
            $this->invoice_date === $this->due_date &&
            $this->payment_terms === 'Cash Payment';
    }
}
