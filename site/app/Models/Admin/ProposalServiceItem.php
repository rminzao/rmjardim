<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_service_id',
        'description',
        'unit',
        'quantity',
        'unit_price',
        'total',
        'order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'order' => 'integer',
    ];

    public function proposalService()
    {
        return $this->belongsTo(ProposalService::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total = $item->quantity * $item->unit_price;
        });
    }
}