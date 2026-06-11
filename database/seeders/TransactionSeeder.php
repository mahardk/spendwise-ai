<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) return;

        $transactions = [];

        $months = ['01', '02', '03', '04', '05'];
        
        foreach ($months as $month) {
            // Income Tetap (Gaji)
            $transactions[] = ['type' => 'income', 'category' => 'Salary', 'amount' => 5000000, 'description' => 'Gaji bulanan', 'transaction_date' => "2026-$month-01"];
            
            // Income Tambahan (Random Freelance)
            if (rand(0, 1)) {
                $transactions[] = ['type' => 'income', 'category' => 'Freelance', 'amount' => rand(10, 25) * 100000, 'description' => 'Project sampingan', 'transaction_date' => "2026-$month-" . str_pad(rand(5, 15), 2, '0', STR_PAD_LEFT)];
            }

            // Pengeluaran Tetap (Bills & Entertainment)
            $transactions[] = ['type' => 'expense', 'category' => 'Bills', 'amount' => 200000, 'description' => 'Listrik & internet', 'transaction_date' => "2026-$month-05"];
            $transactions[] = ['type' => 'expense', 'category' => 'Entertainment', 'amount' => 99000, 'description' => 'Netflix bulanan', 'transaction_date' => "2026-$month-08"];
            
            // Pengeluaran Variabel (Food, Transport) diakumulasi untuk simulasi
            $transactions[] = ['type' => 'expense', 'category' => 'Food', 'amount' => rand(80, 150) * 10000, 'description' => 'Groceries & Makan', 'transaction_date' => "2026-$month-" . str_pad(rand(10, 20), 2, '0', STR_PAD_LEFT)];
            $transactions[] = ['type' => 'expense', 'category' => 'Transport', 'amount' => rand(20, 40) * 10000, 'description' => 'Bensin & Transportasi', 'transaction_date' => "2026-$month-" . str_pad(rand(10, 20), 2, '0', STR_PAD_LEFT)];
            
            // Random Shopping/Health
            if (rand(0, 1)) {
                $transactions[] = ['type' => 'expense', 'category' => 'Shopping', 'amount' => rand(20, 50) * 10000, 'description' => 'Belanja kebutuhan', 'transaction_date' => "2026-$month-" . str_pad(rand(15, 25), 2, '0', STR_PAD_LEFT)];
            } else {
                $transactions[] = ['type' => 'expense', 'category' => 'Health', 'amount' => rand(5, 15) * 10000, 'description' => 'Apotek & Vitamin', 'transaction_date' => "2026-$month-" . str_pad(rand(15, 25), 2, '0', STR_PAD_LEFT)];
            }
        }

        // 2. Tambahkan data spesifik Anda untuk bulan Juni 2026
        $juneTransactions = [
            // Income
            ['type' => 'income', 'category' => 'Salary',      'amount' => 5000000, 'description' => 'Uang jajan bulan Juni',         'transaction_date' => '2026-06-01'],
            ['type' => 'income', 'category' => 'Freelance',   'amount' => 1500000, 'description' => 'Project desain logo',      'transaction_date' => '2026-06-05'],
            ['type' => 'income', 'category' => 'Other',       'amount' => 300000,  'description' => 'Jual barang bekas',        'transaction_date' => '2026-06-08'],

            // Expense
            ['type' => 'expense', 'category' => 'Food',        'amount' => 85000,  'description' => 'Makan siang + kopi',       'transaction_date' => '2026-06-02'],
            ['type' => 'expense', 'category' => 'Food',        'amount' => 120000, 'description' => 'Groceries mingguan',       'transaction_date' => '2026-06-03'],
            ['type' => 'expense', 'category' => 'Transport',   'amount' => 50000,  'description' => 'Bensin motor',             'transaction_date' => '2026-06-04'],
            ['type' => 'expense', 'category' => 'Shopping',    'amount' => 350000, 'description' => 'Beli baju',                'transaction_date' => '2026-06-05'],
            ['type' => 'expense', 'category' => 'Bills',       'amount' => 200000, 'description' => 'Listrik & internet',       'transaction_date' => '2026-06-06'],
            ['type' => 'expense', 'category' => 'Health',      'amount' => 75000,  'description' => 'Vitamin & obat',           'transaction_date' => '2026-06-07'],
            ['type' => 'expense', 'category' => 'Entertainment','amount' => 99000, 'description' => 'Netflix bulanan',          'transaction_date' => '2026-06-08'],
            ['type' => 'expense', 'category' => 'Food',        'amount' => 65000,  'description' => 'GoFood dinner',            'transaction_date' => '2026-06-09'],
            ['type' => 'expense', 'category' => 'Transport',   'amount' => 35000,  'description' => 'Grab ke mall',             'transaction_date' => '2026-06-10'],
            ['type' => 'expense', 'category' => 'Shopping',    'amount' => 180000, 'description' => 'Skincare',                 'transaction_date' => '2026-06-10'],
        ];

        $allTransactions = array_merge($transactions, $juneTransactions);

        foreach ($allTransactions as $t) {
            Transaction::create(array_merge($t, ['user_id' => $user->id]));
        }
    }
}