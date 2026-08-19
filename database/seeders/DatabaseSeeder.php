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

        // Supervisors (SPV)
        $spv1 = User::create([
            'name' => 'SPV - Budi Santoso',
            'username' => 'spv_budi',
            'email' => 'budi@inventory.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=0D8ABC&color=fff',
        ]);

        $spv2 = User::create([
            'name' => 'SPV - Siti Rahma',
            'username' => 'spv_siti',
            'email' => 'siti@inventory.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'avatar' => 'https://ui-avatars.com/api/?name=Siti+Rahma&background=E91E63&color=fff',
        ]);

        // Warehouse Operators (User)
        $op1 = User::create([
            'name' => 'Operator Mikaela',
            'username' => 'op_mikaela',
            'email' => 'mikaela@inventory.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'supervisor_id' => $spv1->id,
            'avatar' => 'https://ui-avatars.com/api/?name=Operator+Mikaela&background=10B981&color=fff',
        ]);

        $op2 = User::create([
            'name' => 'Operator Alex',
            'username' => 'op_alex',
            'email' => 'alex@inventory.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'supervisor_id' => $spv1->id,
            'avatar' => 'https://ui-avatars.com/api/?name=Operator+Alex&background=F59E0B&color=fff',
        ]);

        $op3 = User::create([
            'name' => 'Operator Denny',
            'username' => 'op_denny',
            'email' => 'denny@inventory.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'supervisor_id' => $spv2->id,
            'avatar' => 'https://ui-avatars.com/api/?name=Operator+Denny&background=6366F1&color=fff',
        ]);

        // 2. Create Items (12 Items with In Stock, Low Stock, and Out of Stock variations)
        $itemsData = [
            [
                'sku' => 'SKU-ELEK-001',
                'qr_code_payload' => 'QR-ELEK-001-HNY',
                'name' => 'Barcode Scanner Wireless Honeywell 1472g',
                'location_bin' => 'AISLE 1, BIN A-01',
                'available_stock' => 42,
                'minimum_stock' => 5,
                'image_url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=300',
            ],
            [
                'sku' => 'SKU-PRIN-002',
                'qr_code_payload' => 'QR-PRIN-002-ZBR',
                'name' => 'Thermal Barcode Printer Zebra ZD220',
                'location_bin' => 'AISLE 1, BIN A-02',
                'available_stock' => 15,
                'minimum_stock' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=300',
            ],
            [
                'sku' => 'SKU-CABL-003',
                'qr_code_payload' => 'QR-CABL-003-CAT6',
                'name' => 'Kabel LAN UTP Belden Cat6 305m',
                'location_bin' => 'AISLE 2, BIN B-05',
                'available_stock' => 3, // LOW STOCK
                'minimum_stock' => 5,
                'image_url' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=300',
            ],
            [
                'sku' => 'SKU-PAL-004',
                'qr_code_payload' => 'QR-PAL-004-HDPE',
                'name' => 'Palet Plastik Heavy Duty 120x100 cm',
                'location_bin' => 'AISLE 3, BIN C-01',
                'available_stock' => 0, // OUT OF STOCK
                'minimum_stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?w=300',
            ],
            [
                'sku' => 'SKU-BOX-005',
                'qr_code_payload' => 'QR-BOX-005-KRT',
                'name' => 'Kardus Packing Double Wall 40x30x30',
                'location_bin' => 'AISLE 3, BIN C-04',
                'available_stock' => 250,
                'minimum_stock' => 50,
                'image_url' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?w=300',
            ],
            [
                'sku' => 'SKU-SAFE-006',
                'qr_code_payload' => 'QR-SAFE-006-GLV',
                'name' => 'Sarung Tangan Safety Anti Slip Nitro',
                'location_bin' => 'AISLE 4, BIN D-02',
                'available_stock' => 2, // LOW STOCK
                'minimum_stock' => 15,
                'image_url' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300',
            ],
            [
                'sku' => 'SKU-TOOL-007',
                'qr_code_payload' => 'QR-TOOL-007-DWL',
                'name' => 'Mesin Bor Cordless DeWalt 20V MAX',
                'location_bin' => 'AISLE 4, BIN D-08',
                'available_stock' => 8,
                'minimum_stock' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=300',
            ],
            [
                'sku' => 'SKU-TAPE-008',
                'qr_code_payload' => 'QR-TAPE-008-FRG',
                'name' => 'Lakban Fragile Merah 2 Inch (Roll)',
                'location_bin' => 'AISLE 2, BIN B-01',
                'available_stock' => 0, // OUT OF STOCK
                'minimum_stock' => 20,
                'image_url' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300',
            ],
            [
                'sku' => 'SKU-SEAL-009',
                'qr_code_payload' => 'QR-SEAL-009-SEC',
                'name' => 'Segel Plastik Kontainer Numbered Red',
                'location_bin' => 'AISLE 5, BIN E-03',
                'available_stock' => 4, // LOW STOCK
                'minimum_stock' => 25,
                'image_url' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=300',
            ],
            [
                'sku' => 'SKU-FOL-010',
                'qr_code_payload' => 'QR-FOL-010-STR',
                'name' => 'Stretch Film Wrapping Bening 50cm',
                'location_bin' => 'AISLE 5, BIN E-07',
                'available_stock' => 35,
                'minimum_stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1607613009820-a29f7bb81c04?w=300',
            ],
            [
                'sku' => 'SKU-BATT-011',
                'qr_code_payload' => 'QR-BATT-011-LITH',
                'name' => 'Baterai Li-FePO4 Forklift 48V 400Ah',
                'location_bin' => 'AISLE 6, BIN F-01',
                'available_stock' => 1, // LOW STOCK
                'minimum_stock' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=300',
            ],
            [
                'sku' => 'SKU-VEST-012',
                'qr_code_payload' => 'QR-VEST-012-RFL',
                'name' => 'Rompi Safety K3 Hijau Stabilo Reflektif',
                'location_bin' => 'AISLE 4, BIN D-01',
                'available_stock' => 60,
                'minimum_stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=300',
            ],
        ];

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
