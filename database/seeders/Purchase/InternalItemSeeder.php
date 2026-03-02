<?php

namespace Database\Seeders\Purchase;

use App\Models\Purchase\InternalItem; // Check of je namespace klopt
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InternalItemSeeder extends Seeder
{
    public function run(): void
    {
        $totalItems = 1000;
        $chunkSize = 500; // We doen het in 2 vlotten van 500 voor stabiliteit
        $now = now()->format('Y-m-d H:i:s'); // Harde SQL-vriendelijke string

        $this->command->getOutput()->progressStart($totalItems);

        for ($i = 0; $i < ($totalItems / $chunkSize); $i++) {
            // We gebruiken 'make()' ipv 'create()' zodat er nog GEEN database query is
            $items = \App\Models\Purchase\InternalItem::factory()
                ->count($chunkSize)
                ->make()
                ->map(function ($item) use ($now) {
                    $array = $item->toArray();
                    // Overschrijf de ISO-string met een SQL-string
                    $array['created_at'] = $now;
                    $array['updated_at'] = $now;
                    return $array;
                })
                ->toArray();

            // Mass insert
            DB::table('po_internal_items')->insert($items);

            $this->command->getOutput()->progressAdvance($chunkSize);
        }

        $this->command->getOutput()->progressFinish();
    }
}