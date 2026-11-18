<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\JastipItem;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => "Royce' Nama Chocolate Au Lait & Matcha",
                'origin_country' => 'Jepang',
                'origin_city' => 'Tokyo, Jepang',
                'category' => 'Cokelat & Olahan Kakao',
                'estimated_shipping' => '4 - 6 Hari (Jepang → Indonesia)',
                'price' => 235000,
                'quota' => 14,
                'weight' => '190 gr',
                'po_close_date' => now()->addDays(4),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Cokelat batang & ganache lembut khas Hokkaido dalam kemasan segel dingin.',
                'image' => 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=600',
            ],
            [
                'name' => 'Läderach FrischSchoggi Praline & Truffle Box',
                'origin_country' => 'Swiss',
                'origin_city' => 'Zurich, Swiss',
                'category' => 'Cokelat & Olahan Kakao',
                'estimated_shipping' => '6 - 9 Hari (Eropa → Indonesia)',
                'price' => 420000,
                'quota' => 8,
                'weight' => '250 gr',
                'po_close_date' => now()->addDays(5),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Cokelat truffle artisan Swiss dengan kacang panggang utuh.',
                'image' => 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=600',
            ],
            [
                'name' => 'Tokyo Banana & Shiroi Koibito Bundling',
                'origin_country' => 'Jepang',
                'origin_city' => 'Osaka, Jepang',
                'category' => 'Biskuit, Kue Kering & Pastry Tersegel',
                'estimated_shipping' => '4 - 6 Hari (Jepang → Indonesia)',
                'price' => 260000,
                'quota' => 15,
                'weight' => '450 gr',
                'po_close_date' => now()->addDays(3),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Biskuit lidah kucing cokelat putih dan sponge cake pisang kemasan pabrik.',
                'image' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=600',
            ],
            [
                'name' => 'Ladurée Paris Macarons & Lotus Biscoff Speculoos',
                'origin_country' => 'Prancis',
                'origin_city' => 'Paris, Prancis',
                'category' => 'Biskuit, Kue Kering & Pastry Tersegel',
                'estimated_shipping' => '6 - 9 Hari (Eropa → Indonesia)',
                'price' => 380000,
                'quota' => 10,
                'weight' => '320 gr',
                'po_close_date' => now()->addDays(6),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Macaron kemasan segel resmi butik Paris dan biskuit karamel edisi Eropa.',
                'image' => 'https://images.unsplash.com/photo-1569864358642-9d1684040f43?w=600',
            ],
            [
                'name' => 'Calbee Jagabee & Garrett Popcorn Chicago Mix',
                'origin_country' => 'Singapura',
                'origin_city' => 'Singapura',
                'category' => 'Camilan Ringan Kemasan (Snacks & Chips)',
                'estimated_shipping' => '2 - 3 Hari (Singapura → Indonesia)',
                'price' => 175000,
                'quota' => 20,
                'weight' => '280 gr',
                'po_close_date' => now()->addDays(2),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Keripik kentang renyah dan popcorn karamel-keju dalam kemasan segel pabrik.',
                'image' => 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=600',
            ],
            [
                'name' => 'Kasugai Fruit Gummy & Konpeito Sugar Candy',
                'origin_country' => 'Jepang',
                'origin_city' => 'Kyoto, Jepang',
                'category' => 'Permen & Manisan Kemasan',
                'estimated_shipping' => '4 - 6 Hari (Jepang → Indonesia)',
                'price' => 95000,
                'quota' => 25,
                'weight' => '180 gr',
                'po_close_date' => now()->addDays(4),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Permen jeli sari buah persik & anggur Kyoho kemasan segel pabrik.',
                'image' => 'https://images.unsplash.com/photo-1582058091505-f87a2e55a40f?w=600',
            ],
            [
                'name' => 'Samyang Buldak Limited & Ichiran Ramen Box',
                'origin_country' => 'Korea Selatan',
                'origin_city' => 'Seoul, Korea Selatan',
                'category' => 'Mie Instan & Bumbu/Saus Kemasan Segel',
                'estimated_shipping' => '4 - 6 Hari (Korea Selatan → Indonesia)',
                'price' => 195000,
                'quota' => 18,
                'weight' => '550 gr',
                'po_close_date' => now()->addDays(5),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Mie instan non-daging segar bebas isu PMK serta pasta kari padat kemasan pabrik.',
                'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=600',
            ],
            [
                'name' => 'TWG Tea 1837 Black Tea & Uji Ceremonial Matcha',
                'origin_country' => 'Singapura',
                'origin_city' => 'Singapura',
                'category' => 'Kopi, Teh & Bubuk Minuman',
                'estimated_shipping' => '2 - 3 Hari (Singapura → Indonesia)',
                'price' => 310000,
                'quota' => 12,
                'weight' => '200 gr',
                'po_close_date' => now()->addDays(3),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Teh celup katun premium TWG dan bubuk matcha hijau murni Kyoto.',
                'image' => 'https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?w=600',
            ],
            [
                'name' => 'Wakodo Baby Biscuit & Gerber Organic Puffs',
                'origin_country' => 'Jepang',
                'origin_city' => 'Tokyo, Jepang',
                'category' => 'Makanan Bayi & Suplemen Makanan',
                'estimated_shipping' => '4 - 6 Hari (Jepang → Indonesia)',
                'price' => 125000,
                'quota' => 16,
                'weight' => '150 gr',
                'po_close_date' => now()->addDays(4),
                'jastiper_name' => 'Rimanda Santa',
                'description' => 'Biskuit bayi kaya kalsium kemasan pabrik tersegel aman Bea Cukai.',
                'image' => 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?w=600',
            ],
        ];

        foreach ($items as $item) {
            JastipItem::create($item);
        }

        // 4 Contoh Status Pesanan Berurutan (Tahap 1, 2, 3, dan 4)
        $sampleOrders = [
            [
                'jastip_item_id' => 1,
                'order_code' => 'TG-COQ101',
                'buyer_name' => 'Rimanda Santa',
                'buyer_email' => 'rimandasnt@gmail.com',
                'buyer_phone' => '0812-6489-2026',
                'shipping_address' => 'Sitoluama, Laguboti, Toba, Sumatera Utara',
                'quantity' => 2,
                'subtotal' => 470000,
                'service_fee' => 5000,
                'total_price' => 475000,
                'payment_method' => 'BCA Virtual Account',
                'payment_status' => 'Lunas',
                'step_status' => 1,
                'status_label' => '1. Pesanan Diterima & Dibeli di Negara Asal',
                'courier_vehicle' => null,
                'courier_plate' => null,
            ],
            [
                'jastip_item_id' => 3,
                'order_code' => 'TG-COQ102',
                'buyer_name' => 'Rimanda Santa',
                'buyer_email' => 'rimandasnt@gmail.com',
                'buyer_phone' => '0812-6489-2026',
                'shipping_address' => 'Sitoluama, Laguboti, Toba, Sumatera Utara',
                'quantity' => 1,
                'subtotal' => 260000,
                'service_fee' => 5000,
                'total_price' => 265000,
                'payment_method' => 'PayPal',
                'payment_status' => 'Lunas',
                'step_status' => 2,
                'status_label' => '2. Lolos Bea Cukai & Penerbangan ke Indonesia',
                'courier_vehicle' => null,
                'courier_plate' => null,
            ],
            [
                'jastip_item_id' => 5,
                'order_code' => 'TG-COQ103',
                'buyer_name' => 'Rimanda Santa',
                'buyer_email' => 'rimandasnt@gmail.com',
                'buyer_phone' => '0812-6489-2026',
                'shipping_address' => 'Sitoluama, Laguboti, Toba, Sumatera Utara',
                'quantity' => 2,
                'subtotal' => 350000,
                'service_fee' => 5000,
                'total_price' => 355000,
                'payment_method' => 'BNI Mobile',
                'payment_status' => 'Lunas',
                'step_status' => 3,
                'status_label' => '3. Kurir Lokal Menuju Alamat Pelanggan',
                'courier_vehicle' => 'Motor Honda Vario 160 (Pink Pastel Box)',
                'courier_plate' => 'BB 4829 TG',
            ],
            [
                'jastip_item_id' => 8,
                'order_code' => 'TG-COQ104',
                'buyer_name' => 'Rimanda Santa',
                'buyer_email' => 'rimandasnt@gmail.com',
                'buyer_phone' => '0812-6489-2026',
                'shipping_address' => 'Sitoluama, Laguboti, Toba, Sumatera Utara',
                'quantity' => 1,
                'subtotal' => 310000,
                'service_fee' => 5000,
                'total_price' => 315000,
                'payment_method' => 'Amazon Wallet',
                'payment_status' => 'Lunas',
                'step_status' => 4,
                'status_label' => '4. Pesanan Selesai & Diterima Pelanggan',
                'courier_vehicle' => 'Mobil Daihatsu Sigra White',
                'courier_plate' => 'BK 1920 RS',
            ],
        ];

        foreach ($sampleOrders as $ord) {
            Order::create($ord);
        }

        // Rekening Bank Default
        BankAccount::create(['provider' => 'BNI', 'account_number' => '1829304412', 'account_holder' => 'Rimanda Santa', 'is_primary' => true]);
        BankAccount::create(['provider' => 'BRI', 'account_number' => '034101092837502', 'account_holder' => 'Rimanda Santa', 'is_primary' => false]);
        BankAccount::create(['provider' => 'PayPal', 'account_number' => 'rimandasnt@gmail.com', 'account_holder' => 'Rimanda Santa', 'is_primary' => false]);
        BankAccount::create(['provider' => 'Amazon Wallet', 'account_number' => 'AMZ-ID-992817', 'account_holder' => 'Rimanda Santa', 'is_primary' => false]);

        // Notifikasi Awal
        Notification::create(['role' => 'buyer', 'title' => 'Kurir Lokal Sedang Mengantar #TG-COQ103', 'message' => 'Pesanan Calbee & Garrett Popcorn diantar menggunakan Motor Honda Vario 160 (BB 4829 TG).']);
        Notification::create(['role' => 'buyer', 'title' => 'Pembayaran Berhasil #TG-COQ101', 'message' => 'Pembayaran Rp 475.000 via BCA Virtual Account telah terverifikasi.']);
        Notification::create(['role' => 'jastiper', 'title' => 'Pesanan Baru Masuk #TG-COQ101', 'message' => 'Rimanda Santa memesan 2x Royce Nama Chocolate dari Tokyo, Jepang.']);
        Notification::create(['role' => 'jastiper', 'title' => 'Dana Siap Dicairkan', 'message' => 'Pendapatan PO minggu ini telah terakumulasi dan siap ditarik ke rekening bank Anda.']);
    }
}