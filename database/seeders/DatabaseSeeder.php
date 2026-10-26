<?php
// backend/database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──────────────────────────────────────────────
        User::insert([
            [
                'name'       => 'Super Admin',
                'username'   => 'admin',
                'email'      => 'admin@billingpro.com',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Cashier One',
                'username'   => 'cashier',
                'email'      => 'cashier@billingpro.com',
                'password'   => Hash::make('cash123'),
                'role'       => 'cashier',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Cashier Two',
                'username'   => 'cashier2',
                'email'      => 'cashier2@billingpro.com',
                'password'   => Hash::make('cash123'),
                'role'       => 'cashier',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ── Categories ─────────────────────────────────────────
        $categories = [
            // Supermarket
            ['name' => 'Groceries',        'description' => 'Daily grocery items',            'store_type' => 'Supermarket'],
            ['name' => 'Beverages',         'description' => 'Drinks and juices',              'store_type' => 'Supermarket'],
            ['name' => 'Dairy Products',    'description' => 'Milk, cheese, butter',           'store_type' => 'Supermarket'],
            ['name' => 'Snacks',            'description' => 'Biscuits, chips, namkeen',       'store_type' => 'Supermarket'],
            ['name' => 'Personal Care',     'description' => 'Soap, shampoo, cosmetics',       'store_type' => 'Supermarket'],
            ['name' => 'Household',         'description' => 'Cleaning and home items',        'store_type' => 'Supermarket'],
            // Fancy/Fashion
            ['name' => 'Clothing',          'description' => 'Mens, womens, kids clothes',     'store_type' => 'Fancy/Fashion'],
            ['name' => 'Footwear',          'description' => 'Shoes, sandals, slippers',       'store_type' => 'Fancy/Fashion'],
            ['name' => 'Accessories',       'description' => 'Jewellery, bags, belts',         'store_type' => 'Fancy/Fashion'],
            ['name' => 'Cosmetics',         'description' => 'Makeup and beauty products',     'store_type' => 'Fancy/Fashion'],
            // Construction
            ['name' => 'Building Materials','description' => 'Cement, bricks, sand',           'store_type' => 'Construction'],
            ['name' => 'Paints',            'description' => 'Wall paints and primers',        'store_type' => 'Construction'],
            ['name' => 'Plumbing',          'description' => 'Pipes, fittings, taps',          'store_type' => 'Construction'],
            ['name' => 'Electrical',        'description' => 'Wires, switches, bulbs',         'store_type' => 'Construction'],
            ['name' => 'Hardware',          'description' => 'Nuts, bolts, tools',             'store_type' => 'Construction'],
            ['name' => 'Steel & Iron',      'description' => 'Rods, sheets, angles',           'store_type' => 'Construction'],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, ['created_at' => now(), 'updated_at' => now()]));
        }

        // ── Products ───────────────────────────────────────────
        $products = [
            // Supermarket - Groceries (cat_id = 1)
            ['name' => 'Basmati Rice 5kg',        'barcode' => '8901234000001', 'category_id' => 1,  'price' => 350,   'cost_price' => 280,  'stock' => 85,  'min_stock' => 10, 'unit' => 'bag',   'store_type' => 'Supermarket'],
            ['name' => 'Wheat Flour (Atta) 5kg',  'barcode' => '8901234000002', 'category_id' => 1,  'price' => 220,   'cost_price' => 180,  'stock' => 60,  'min_stock' => 10, 'unit' => 'bag',   'store_type' => 'Supermarket'],
            ['name' => 'Sugar 1kg',               'barcode' => '8901234000003', 'category_id' => 1,  'price' => 48,    'cost_price' => 40,   'stock' => 200, 'min_stock' => 20, 'unit' => 'kg',    'store_type' => 'Supermarket'],
            ['name' => 'Salt 1kg',                'barcode' => '8901234000004', 'category_id' => 1,  'price' => 22,    'cost_price' => 18,   'stock' => 150, 'min_stock' => 20, 'unit' => 'kg',    'store_type' => 'Supermarket'],
            ['name' => 'Toor Dal 1kg',            'barcode' => '8901234000005', 'category_id' => 1,  'price' => 135,   'cost_price' => 110,  'stock' => 75,  'min_stock' => 15, 'unit' => 'kg',    'store_type' => 'Supermarket'],
            ['name' => 'Sunflower Oil 1L',        'barcode' => '8901234000006', 'category_id' => 1,  'price' => 145,   'cost_price' => 120,  'stock' => 80,  'min_stock' => 10, 'unit' => 'litre', 'store_type' => 'Supermarket'],
            ['name' => 'Mustard Oil 1L',          'barcode' => '8901234000007', 'category_id' => 1,  'price' => 155,   'cost_price' => 128,  'stock' => 45,  'min_stock' => 10, 'unit' => 'litre', 'store_type' => 'Supermarket'],
            // Dairy (cat_id = 3)
            ['name' => 'Amul Full Cream Milk 1L', 'barcode' => '8901234000008', 'category_id' => 3,  'price' => 68,    'cost_price' => 60,   'stock' => 40,  'min_stock' => 10, 'unit' => 'litre', 'store_type' => 'Supermarket'],
            ['name' => 'Amul Butter 500g',        'barcode' => '8901234000009', 'category_id' => 3,  'price' => 268,   'cost_price' => 240,  'stock' => 25,  'min_stock' => 5,  'unit' => 'pcs',   'store_type' => 'Supermarket'],
            // Snacks (cat_id = 4)
            ['name' => 'Parle-G Biscuit 800g',    'barcode' => '8901234000010', 'category_id' => 4,  'price' => 95,    'cost_price' => 75,   'stock' => 60,  'min_stock' => 10, 'unit' => 'pcs',   'store_type' => 'Supermarket'],
            // Beverages (cat_id = 2)
            ['name' => 'Coca Cola 600ml',         'barcode' => '8901234000011', 'category_id' => 2,  'price' => 40,    'cost_price' => 30,   'stock' => 100, 'min_stock' => 20, 'unit' => 'pcs',   'store_type' => 'Supermarket'],
            ['name' => 'Frooti 200ml',            'barcode' => '8901234000012', 'category_id' => 2,  'price' => 20,    'cost_price' => 15,   'stock' => 80,  'min_stock' => 20, 'unit' => 'pcs',   'store_type' => 'Supermarket'],
            // Personal Care (cat_id = 5)
            ['name' => 'Pantene Shampoo 340ml',   'barcode' => '8901234000013', 'category_id' => 5,  'price' => 245,   'cost_price' => 195,  'stock' => 30,  'min_stock' => 5,  'unit' => 'pcs',   'store_type' => 'Supermarket'],
            ['name' => 'Colgate Paste 200g',      'barcode' => '8901234000014', 'category_id' => 5,  'price' => 95,    'cost_price' => 78,   'stock' => 40,  'min_stock' => 10, 'unit' => 'pcs',   'store_type' => 'Supermarket'],
            ['name' => 'Lux Soap 100g',           'barcode' => '8901234000015', 'category_id' => 5,  'price' => 48,    'cost_price' => 38,   'stock' => 80,  'min_stock' => 20, 'unit' => 'pcs',   'store_type' => 'Supermarket'],
            // Household (cat_id = 6)
            ['name' => 'Surf Excel 1kg',          'barcode' => '8901234000016', 'category_id' => 6,  'price' => 215,   'cost_price' => 175,  'stock' => 50,  'min_stock' => 10, 'unit' => 'kg',    'store_type' => 'Supermarket'],
            ['name' => 'Vim Bar 200g',            'barcode' => '8901234000017', 'category_id' => 6,  'price' => 35,    'cost_price' => 25,   'stock' => 3,   'min_stock' => 15, 'unit' => 'pcs',   'store_type' => 'Supermarket'],

            // Fancy/Fashion - Clothing (cat_id = 7)
            ['name' => 'Ladies Kurti (XL)',       'barcode' => '8902345000001', 'category_id' => 7,  'price' => 850,   'cost_price' => 480,  'stock' => 8,   'min_stock' => 5,  'unit' => 'pcs',   'store_type' => 'Fancy/Fashion'],
            ['name' => 'Mens Formal Shirt (L)',   'barcode' => '8902345000002', 'category_id' => 7,  'price' => 999,   'cost_price' => 550,  'stock' => 12,  'min_stock' => 5,  'unit' => 'pcs',   'store_type' => 'Fancy/Fashion'],
            ['name' => 'Kids Frock (Age 5-6)',    'barcode' => '8902345000003', 'category_id' => 7,  'price' => 549,   'cost_price' => 300,  'stock' => 6,   'min_stock' => 3,  'unit' => 'pcs',   'store_type' => 'Fancy/Fashion'],
            ['name' => 'Mens T-Shirt (M)',        'barcode' => '8902345000004', 'category_id' => 7,  'price' => 499,   'cost_price' => 280,  'stock' => 15,  'min_stock' => 5,  'unit' => 'pcs',   'store_type' => 'Fancy/Fashion'],
            // Footwear (cat_id = 8)
            ['name' => 'Ladies Sandal Size 7',   'barcode' => '8902345000005', 'category_id' => 8,  'price' => 699,   'cost_price' => 380,  'stock' => 5,   'min_stock' => 3,  'unit' => 'pair',  'store_type' => 'Fancy/Fashion'],
            ['name' => 'Mens Sneakers Size 9',   'barcode' => '8902345000006', 'category_id' => 8,  'price' => 1299,  'cost_price' => 750,  'stock' => 4,   'min_stock' => 3,  'unit' => 'pair',  'store_type' => 'Fancy/Fashion'],
            // Accessories (cat_id = 9)
            ['name' => 'Handbag (Black)',         'barcode' => '8902345000007', 'category_id' => 9,  'price' => 799,   'cost_price' => 420,  'stock' => 7,   'min_stock' => 3,  'unit' => 'pcs',   'store_type' => 'Fancy/Fashion'],
            ['name' => 'Gold Plated Earrings',   'barcode' => '8902345000008', 'category_id' => 9,  'price' => 350,   'cost_price' => 150,  'stock' => 20,  'min_stock' => 5,  'unit' => 'pair',  'store_type' => 'Fancy/Fashion'],
            // Cosmetics (cat_id = 10)
            ['name' => 'Lipstick (Red)',          'barcode' => '8902345000009', 'category_id' => 10, 'price' => 299,   'cost_price' => 150,  'stock' => 15,  'min_stock' => 5,  'unit' => 'pcs',   'store_type' => 'Fancy/Fashion'],
            ['name' => 'Foundation Cream',        'barcode' => '8902345000010', 'category_id' => 10, 'price' => 499,   'cost_price' => 250,  'stock' => 10,  'min_stock' => 3,  'unit' => 'pcs',   'store_type' => 'Fancy/Fashion'],

            // Construction - Building Materials (cat_id = 11)
            ['name' => 'Cement 50kg (OPC)',       'barcode' => '8903456000001', 'category_id' => 11, 'price' => 420,   'cost_price' => 360,  'stock' => 120, 'min_stock' => 20, 'unit' => 'bag',   'store_type' => 'Construction'],
            ['name' => 'AAC Block (9 inch)',      'barcode' => '8903456000002', 'category_id' => 11, 'price' => 35,    'cost_price' => 28,   'stock' => 500, 'min_stock' => 50, 'unit' => 'pcs',   'store_type' => 'Construction'],
            ['name' => 'River Sand (1 cft)',      'barcode' => '8903456000003', 'category_id' => 11, 'price' => 55,    'cost_price' => 40,   'stock' => 200, 'min_stock' => 20, 'unit' => 'cft',   'store_type' => 'Construction'],
            // Paints (cat_id = 12)
            ['name' => 'Asian Paint White 1L',   'barcode' => '8903456000004', 'category_id' => 12, 'price' => 180,   'cost_price' => 140,  'stock' => 35,  'min_stock' => 10, 'unit' => 'litre', 'store_type' => 'Construction'],
            ['name' => 'Berger Primer 1L',        'barcode' => '8903456000005', 'category_id' => 12, 'price' => 145,   'cost_price' => 110,  'stock' => 25,  'min_stock' => 5,  'unit' => 'litre', 'store_type' => 'Construction'],
            ['name' => 'Enamel Paint Black 1L',  'barcode' => '8903456000006', 'category_id' => 12, 'price' => 195,   'cost_price' => 150,  'stock' => 4,   'min_stock' => 5,  'unit' => 'litre', 'store_type' => 'Construction'],
            // Plumbing (cat_id = 13)
            ['name' => 'PVC Pipe 1 inch (1m)',   'barcode' => '8903456000007', 'category_id' => 13, 'price' => 65,    'cost_price' => 48,   'stock' => 80,  'min_stock' => 10, 'unit' => 'pcs',   'store_type' => 'Construction'],
            ['name' => 'Ball Valve 1/2 inch',    'barcode' => '8903456000008', 'category_id' => 13, 'price' => 95,    'cost_price' => 70,   'stock' => 40,  'min_stock' => 10, 'unit' => 'pcs',   'store_type' => 'Construction'],
            // Electrical (cat_id = 14)
            ['name' => 'Copper Wire 1.5mm (1m)', 'barcode' => '8903456000009', 'category_id' => 14, 'price' => 28,    'cost_price' => 20,   'stock' => 300, 'min_stock' => 50, 'unit' => 'meter', 'store_type' => 'Construction'],
            ['name' => 'LED Bulb 9W',             'barcode' => '8903456000010', 'category_id' => 14, 'price' => 85,    'cost_price' => 60,   'stock' => 60,  'min_stock' => 10, 'unit' => 'pcs',   'store_type' => 'Construction'],
            ['name' => 'Switchboard 3-pin',      'barcode' => '8903456000011', 'category_id' => 14, 'price' => 120,   'cost_price' => 85,   'stock' => 2,   'min_stock' => 10, 'unit' => 'pcs',   'store_type' => 'Construction'],
            // Hardware (cat_id = 15)
            ['name' => 'Hammer 500g',             'barcode' => '8903456000012', 'category_id' => 15, 'price' => 180,   'cost_price' => 130,  'stock' => 15,  'min_stock' => 5,  'unit' => 'pcs',   'store_type' => 'Construction'],
            ['name' => 'Measuring Tape 5m',       'barcode' => '8903456000013', 'category_id' => 15, 'price' => 95,    'cost_price' => 65,   'stock' => 20,  'min_stock' => 5,  'unit' => 'pcs',   'store_type' => 'Construction'],
            // Steel (cat_id = 16)
            ['name' => 'TMT Steel Rod 10mm (1m)','barcode' => '8903456000014', 'category_id' => 16, 'price' => 120,   'cost_price' => 95,   'stock' => 80,  'min_stock' => 10, 'unit' => 'meter', 'store_type' => 'Construction'],
            ['name' => 'MS Flat Bar 25x3mm (1m)','barcode' => '8903456000015', 'category_id' => 16, 'price' => 85,    'cost_price' => 65,   'stock' => 50,  'min_stock' => 10, 'unit' => 'meter', 'store_type' => 'Construction'],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('👤 Admin:   admin / admin123');
        $this->command->info('👤 Cashier: cashier / cash123');
    }
}
