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

        $transactions = [
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

        foreach ($transactions as $t) {
            Transaction::create(array_merge($t, ['user_id' => $user->id]));
        }
    }
}