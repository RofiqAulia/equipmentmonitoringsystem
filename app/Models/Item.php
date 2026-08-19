<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'qr_code_payload',
        'name',
        'location_bin',
        'available_stock',
        'minimum_stock',
        'image_url',
    ];

    protected $appends = [
        'stock_status',
    ];

    /**
     * Get calculated stock status ('in_stock', 'low_stock', 'out_of_stock').
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->available_stock <= 0) {
            return 'out_of_stock';
        }

        if ($this->available_stock <= $this->minimum_stock) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    /**
     * Get retrieval transaction logs for this item.
     */
    public function retrievalLogs(): HasMany
    {
        return $this->hasMany(RetrievalLog::class, 'item_id');
    }
}
