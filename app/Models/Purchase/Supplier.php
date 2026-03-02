<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{

    use HasFactory;

    protected $table = 'po_suppliers';
    protected $fillable = ['name', 'vat_number', 'address', 'email', 'phone', 'is_active'];

    public function items(): HasMany
    {
        return $this->hasMany(SupplierItem::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}