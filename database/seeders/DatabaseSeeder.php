<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\RetrievalLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users & Roles
        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@inventory.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'avatar' => 'https://ui-avatars.com/api/?name=Super+Admin&background=1E293B&color=fff',
        ]);

            $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'admin2',
            'email' => 'admin2@inventory.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'avatar' => 'https://ui-avatars.com/api/?name=Super+Admin&background=1E293B&color=fff',
        ]);

        $createdItems = [];
        foreach ($itemsData as $itemData) {
            $createdItems[] = Item::create($itemData);
        }

        // 3. Create 18+ Sample Retrieval Transaction Logs
        $operators = [$op1, $op2, $op3];
        $supervisors = [$spv1, $spv2];
        $sampleNotes = [
            'Pengambilan rutin proyek Assembly Line A',
            'Kebutuhan restock mendesak Zona Shipping',
            'Pengambilan untuk Maintenance Forklift',
            'Pengeluaran barang barang pesanan PO-8821',
            'Pengambilan suku cadang darurat',
            'Pengambilan perlengkapan packing shift pagi',
            'Disetujui untuk operasional harian gudang',
        ];

        for ($i = 1; $i <= 18; $i++) {
            $user = $operators[array_rand($operators)];
            $supervisor = $user->supervisor_id ? User::find($user->supervisor_id) : $supervisors[array_rand($supervisors)];
            $item = $createdItems[array_rand($createdItems)];

            // Randomize picked_at within last 5 days
            $daysAgo = rand(0, 5);
            $hoursAgo = rand(1, 12);
            $pickedAt = Carbon::now()->subDays($daysAgo)->subHours($hoursAgo);

            RetrievalLog::create([
                'user_id' => $user->id,
                'supervisor_id' => $supervisor->id,
                'item_id' => $item->id,
                'quantity_picked' => rand(1, 5),
                'picked_at' => $pickedAt,
                'notes' => $sampleNotes[array_rand($sampleNotes)],
            ]);
        }
    }
}
