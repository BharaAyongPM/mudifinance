<?php

return [
    'app_name' => 'MudiFinance',
    'company_name' => 'MudiFinance',
    'copyright_name' => 'Mustika Digital Nusantara',
    'website' => null,
    'logo_path' => 'images/logo/logo.png',
    'tagline' => 'Aplikasi keuangan multi-user untuk pencatatan pemasukan, pengeluaran, laporan, dan cetak data.',
    'roles' => [
        'admin' => 'Admin',
        'user' => 'User',
    ],
    'default_admin' => [
        'name' => 'Admin MudiFinance',
        'email' => 'admin@mudifinance.app',
        'password' => 'admin12345',
    ],
    'default_accounts' => [
        [
            'name' => 'Kas Utama',
            'type' => 'cash',
            'opening_balance' => 0,
            'description' => 'Kas utama untuk transaksi harian.',
        ],
        [
            'name' => 'Bank Operasional',
            'type' => 'bank',
            'opening_balance' => 0,
            'description' => 'Rekening utama untuk pemasukan dan pengeluaran.',
        ],
        [
            'name' => 'E-Wallet',
            'type' => 'ewallet',
            'opening_balance' => 0,
            'description' => 'Dompet digital untuk pembayaran cepat.',
        ],
    ],
    'default_categories' => [
        ['name' => 'Penjualan Produk', 'type' => 'income', 'color' => '#12B76A', 'icon' => 'cart'],
        ['name' => 'Pendapatan Jasa', 'type' => 'income', 'color' => '#0BA5EC', 'icon' => 'chart'],
        ['name' => 'Pendapatan Lainnya', 'type' => 'income', 'color' => '#7A5AF8', 'icon' => 'gift'],
        ['name' => 'Biaya Operasional', 'type' => 'expense', 'color' => '#F79009', 'icon' => 'briefcase'],
        ['name' => 'Gaji & Honor', 'type' => 'expense', 'color' => '#F04438', 'icon' => 'salary'],
        ['name' => 'Transport & Konsumsi', 'type' => 'expense', 'color' => '#465FFF', 'icon' => 'transport'],
        ['name' => 'Peralatan & Software', 'type' => 'expense', 'color' => '#344054', 'icon' => 'device'],
        ['name' => 'Biaya Lainnya', 'type' => 'expense', 'color' => '#FD853A', 'icon' => 'receipt'],
    ],
    'account_types' => [
        'cash' => 'Kas',
        'bank' => 'Bank',
        'ewallet' => 'E-Wallet',
        'other' => 'Lainnya',
    ],
    'transaction_types' => [
        'income' => 'Pemasukan',
        'expense' => 'Pengeluaran',
    ],
    'transaction_statuses' => [
        'posted' => 'Tercatat',
        'draft' => 'Draft',
    ],
];
