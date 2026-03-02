<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase\Supplier;
use App\Models\Purchase\InternalItem;
use App\Models\Purchase\SupplierItem;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\PurchaseOrderLine;
use App\Models\Purchase\GoodsReceipt;
use App\Models\Purchase\GoodsReceiptLine;
use App\Models\Purchase\SupplierInvoice;
use Carbon\Carbon;
use Database\Seeders\Purchase\SupplierSeeder;
use Database\Seeders\Purchase\InternalItemSeeder;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call(
            [
                // SupplierSeeder::class,
                InternalItemSeeder::class,
            ]
        );

        $this->command->info('Rentman seeding completed successfully!');

    }
}