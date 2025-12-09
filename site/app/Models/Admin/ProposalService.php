<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_document',
        'client_document_type',
        'client_phone',
        'client_email',
        'client_address',
        'client_city',
        'client_state',
        'client_zipcode',
        'company_name',
        'company_trade_name',
        'subtotal',
        'discount',
        'total',
        'payment_conditions',
        'validity_days',
        'notes',
        'status',
        'pdf_path',
        'sent_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'validity_days' => 'integer',
        'sent_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(ProposalServiceItem::class)->orderBy('order');
    }

    public function calculateSubtotal()
    {
        return $this->items()->sum('total');
    }

    public function calculateTotal()
    {
        return $this->subtotal - $this->discount;
    }
}