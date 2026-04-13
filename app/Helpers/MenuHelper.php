<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getCategoryIconOptions(): array
    {
        return [
            'wallet' => [
                'label' => 'Kas & Dompet',
                'description' => 'Cocok untuk kas kecil, uang tunai, dan saldo dompet.',
            ],
            'bank' => [
                'label' => 'Bank & Transfer',
                'description' => 'Dipakai untuk rekening bank, transfer, dan mutasi masuk.',
            ],
            'cart' => [
                'label' => 'Penjualan',
                'description' => 'Ideal untuk omzet, penjualan produk, dan checkout pelanggan.',
            ],
            'chart' => [
                'label' => 'Profit & Jasa',
                'description' => 'Pas untuk pendapatan jasa, profit, komisi, dan investasi.',
            ],
            'briefcase' => [
                'label' => 'Operasional',
                'description' => 'Untuk biaya operasional harian, proyek, dan kebutuhan bisnis.',
            ],
            'receipt' => [
                'label' => 'Tagihan & Belanja',
                'description' => 'Bagus untuk tagihan, pembelian, belanja, dan invoice vendor.',
            ],
            'salary' => [
                'label' => 'Gaji & Honor',
                'description' => 'Cocok untuk payroll, honor, fee tim, dan bonus rutin.',
            ],
            'transport' => [
                'label' => 'Transportasi',
                'description' => 'Untuk bensin, parkir, perjalanan dinas, dan logistik.',
            ],
            'meal' => [
                'label' => 'Konsumsi',
                'description' => 'Dipakai untuk makan, minum, snack rapat, dan katering.',
            ],
            'device' => [
                'label' => 'Peralatan & Software',
                'description' => 'Untuk laptop, perangkat kerja, lisensi, dan langganan tools.',
            ],
            'tax' => [
                'label' => 'Pajak & Admin',
                'description' => 'Pas untuk pajak, biaya admin, dan potongan layanan.',
            ],
            'gift' => [
                'label' => 'Bonus & Lainnya',
                'description' => 'Dipakai untuk bonus, hadiah, cashback, dan pendapatan lain.',
            ],
        ];
    }

    public static function getMenuGroups(): array
    {
        $user = auth()->user();

        $groups = [
            [
                'title' => 'Utama',
                'items' => [
                    [
                        'name' => 'Dashboard',
                        'path' => route('dashboard'),
                        'icon' => 'dashboard',
                    ],
                    [
                        'name' => 'Transaksi',
                        'path' => route('transactions.index'),
                        'icon' => 'transactions',
                    ],
                ],
            ],
            [
                'title' => 'Master Data',
                'items' => [
                    [
                        'name' => 'Akun Kas',
                        'path' => route('accounts.index'),
                        'icon' => 'wallet',
                    ],
                    [
                        'name' => 'Kategori',
                        'path' => route('categories.index'),
                        'icon' => 'category',
                    ],
                    [
                        'name' => 'Unit Bisnis',
                        'path' => route('business-units.index'),
                        'icon' => 'briefcase',
                    ],
                ],
            ],
            [
                'title' => 'Analitik',
                'items' => [
                    [
                        'name' => 'Laporan',
                        'path' => route('reports.index'),
                        'icon' => 'reports',
                    ],
                    [
                        'name' => 'Cetak Laporan',
                        'path' => route('reports.print'),
                        'icon' => 'print',
                    ],
                ],
            ],
        ];

        if ($user?->isAdmin()) {
            $groups[] = [
                'title' => 'Admin',
                'items' => [
                    [
                        'name' => 'Manajemen User',
                        'path' => route('users.index'),
                        'icon' => 'users',
                    ],
                ],
            ];
        }

        return $groups;
    }

    public static function getIconSvg(string $iconName): string
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M4 13.5C4 12.6716 4.67157 12 5.5 12H10C10.8284 12 11.5 12.6716 11.5 13.5V18.5C11.5 19.3284 10.8284 20 10 20H5.5C4.67157 20 4 19.3284 4 18.5V13.5Z" fill="currentColor"/><path d="M12.5 5.5C12.5 4.67157 13.1716 4 14 4H18.5C19.3284 4 20 4.67157 20 5.5V10C20 10.8284 19.3284 11.5 18.5 11.5H14C13.1716 11.5 12.5 10.8284 12.5 10V5.5Z" fill="currentColor"/><path d="M4 5.5C4 4.67157 4.67157 4 5.5 4H10C10.8284 4 11.5 4.67157 11.5 5.5V10C11.5 10.8284 10.8284 11.5 10 11.5H5.5C4.67157 11.5 4 10.8284 4 10V5.5Z" fill="currentColor" opacity=".6"/><path d="M12.5 14C12.5 13.1716 13.1716 12.5 14 12.5H18.5C19.3284 12.5 20 13.1716 20 14V18.5C20 19.3284 19.3284 20 18.5 20H14C13.1716 20 12.5 19.3284 12.5 18.5V14Z" fill="currentColor" opacity=".6"/></svg>',
            'transactions' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M3 6.75C3 5.7835 3.7835 5 4.75 5H19.25C20.2165 5 21 5.7835 21 6.75V17.25C21 18.2165 20.2165 19 19.25 19H4.75C3.7835 19 3 18.2165 3 17.25V6.75Z" stroke="currentColor" stroke-width="1.5"/><path d="M7 9.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M7 14.5H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M16.5 15.75L18.25 14L16.5 12.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'wallet' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M4 8C4 6.89543 4.89543 6 6 6H18C19.1046 6 20 6.89543 20 8V16C20 17.1046 19.1046 18 18 18H6C4.89543 18 4 17.1046 4 16V8Z" stroke="currentColor" stroke-width="1.5"/><path d="M16 12H20" stroke="currentColor" stroke-width="1.5"/><circle cx="15.5" cy="12" r="1.5" fill="currentColor"/></svg>',
            'category' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M11 4.5H5.5C4.94772 4.5 4.5 4.94772 4.5 5.5V11C4.5 11.5523 4.94772 12 5.5 12H11C11.5523 12 12 11.5523 12 11V5.5C12 4.94772 11.5523 4.5 11 4.5Z" stroke="currentColor" stroke-width="1.5"/><path d="M18.5 4.5H14.5C13.9477 4.5 13.5 4.94772 13.5 5.5V9.5C13.5 10.0523 13.9477 10.5 14.5 10.5H18.5C19.0523 10.5 19.5 10.0523 19.5 9.5V5.5C19.5 4.94772 19.0523 4.5 18.5 4.5Z" stroke="currentColor" stroke-width="1.5"/><path d="M18.5 13.5H14.5C13.9477 13.5 13.5 13.9477 13.5 14.5V18.5C13.5 19.0523 13.9477 19.5 14.5 19.5H18.5C19.0523 19.5 19.5 19.0523 19.5 18.5V14.5C19.5 13.9477 19.0523 13.5 18.5 13.5Z" stroke="currentColor" stroke-width="1.5"/><path d="M8.25 14L10.4017 17.5H6.0983L8.25 14Z" fill="currentColor"/></svg>',
            'briefcase' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M8 7V6.5C8 5.39543 8.89543 4.5 10 4.5H14C15.1046 4.5 16 5.39543 16 6.5V7" stroke="currentColor" stroke-width="1.5"/><path d="M5 8H19C20.1046 8 21 8.89543 21 10V17.5C21 18.6046 20.1046 19.5 19 19.5H5C3.89543 19.5 3 18.6046 3 17.5V10C3 8.89543 3.89543 8 5 8Z" stroke="currentColor" stroke-width="1.5"/><path d="M3 12H21" stroke="currentColor" stroke-width="1.5"/><path d="M10 14.5H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
            'reports' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M7 4.5H14.5L19 9V18.5C19 19.0523 18.5523 19.5 18 19.5H7C5.89543 19.5 5 18.6046 5 17.5V6.5C5 5.39543 5.89543 4.5 7 4.5Z" stroke="currentColor" stroke-width="1.5"/><path d="M14 4.5V9H18.5" stroke="currentColor" stroke-width="1.5"/><path d="M8.5 14.5H15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8.5 11.5H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
            'print' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M7 9V4.5H17V9" stroke="currentColor" stroke-width="1.5"/><path d="M7 15H5C3.89543 15 3 14.1046 3 13V11C3 9.89543 3.89543 9 5 9H19C20.1046 9 21 9.89543 21 11V13C21 14.1046 20.1046 15 19 15H17" stroke="currentColor" stroke-width="1.5"/><path d="M7 12.5H17V19.5H7V12.5Z" stroke="currentColor" stroke-width="1.5"/><circle cx="17" cy="11.5" r="1" fill="currentColor"/></svg>',
            'users' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M16 19C16 17.3431 14.2091 16 12 16C9.79086 16 8 17.3431 8 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="9" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M19 8.5C20.1046 8.5 21 9.39543 21 10.5C21 11.6046 20.1046 12.5 19 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M5 8.5C3.89543 8.5 3 9.39543 3 10.5C3 11.6046 3.89543 12.5 5 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
        ];

        return $icons[$iconName] ?? $icons['dashboard'];
    }

    public static function getCategoryIconSvg(string $iconName): string
    {
        $icons = [
            'wallet' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 8C4 6.89543 4.89543 6 6 6H18C19.1046 6 20 6.89543 20 8V16C20 17.1046 19.1046 18 18 18H6C4.89543 18 4 17.1046 4 16V8Z" stroke="currentColor" stroke-width="1.7"/><path d="M16 12H20" stroke="currentColor" stroke-width="1.7"/><circle cx="15.5" cy="12" r="1.6" fill="currentColor"/></svg>',
            'bank' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 10.5L12 5L20 10.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.5 10.5V18.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M9.5 10.5V18.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M14.5 10.5V18.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M18.5 10.5V18.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M4 19H20" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
            'cart' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 6H6.2C6.66304 6 7.06433 6.31984 7.16795 6.77114L7.5 8.2M7.5 8.2L8.6 13.1C8.71416 13.6088 9.16552 13.97 9.687 13.97H17.8C18.2789 13.97 18.6986 13.6506 18.826 13.1889L20 8.9C20.1824 8.23358 19.6811 7.58 18.9902 7.58H8.08" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10.5" cy="18" r="1.5" fill="currentColor"/><circle cx="17" cy="18" r="1.5" fill="currentColor"/></svg>',
            'chart' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 19V11" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M12 19V5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M19 19V9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M4 19H20" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
            'briefcase' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M8 7V6.5C8 5.39543 8.89543 4.5 10 4.5H14C15.1046 4.5 16 5.39543 16 6.5V7" stroke="currentColor" stroke-width="1.7"/><path d="M5 8H19C20.1046 8 21 8.89543 21 10V17.5C21 18.6046 20.1046 19.5 19 19.5H5C3.89543 19.5 3 18.6046 3 17.5V10C3 8.89543 3.89543 8 5 8Z" stroke="currentColor" stroke-width="1.7"/><path d="M3 12H21" stroke="currentColor" stroke-width="1.7"/><path d="M10 14.5H14" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
            'receipt' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M7 4.5H17V19.5L15.1 18.2L13.2 19.5L11.3 18.2L9.4 19.5L7.5 18.2L5.5 19.5V6C5.5 5.17157 6.17157 4.5 7 4.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M9 9H15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M9 12.5H15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M9 16H13" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
            'salary' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><rect x="4" y="6" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M12 9.2C10.6745 9.2 9.6 10.2745 9.6 11.6C9.6 12.9255 10.6745 14 12 14C13.3255 14 14.4 12.9255 14.4 11.6C14.4 10.2745 13.3255 9.2 12 9.2Z" stroke="currentColor" stroke-width="1.7"/><path d="M7.5 9H7.51" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><path d="M16.5 14.2H16.51" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>',
            'transport' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M7 16V9.8C7 8.80589 7.80589 8 8.8 8H15.2C16.1941 8 17 8.80589 17 9.8V16" stroke="currentColor" stroke-width="1.7"/><path d="M9 8L10.1 5.8C10.4381 5.12375 11.1293 4.7 11.8854 4.7H12.1146C12.8707 4.7 13.5619 5.12375 13.9 5.8L15 8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M7 13H17" stroke="currentColor" stroke-width="1.7"/><circle cx="9.2" cy="16.8" r="1.5" fill="currentColor"/><circle cx="14.8" cy="16.8" r="1.5" fill="currentColor"/></svg>',
            'meal' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M8 4.5V11" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M6 4.5V8.5C6 9.88071 6.89543 11 8 11C9.10457 11 10 9.88071 10 8.5V4.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M8 11V19.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M15 4.5C16.6569 4.5 18 5.84315 18 7.5V19.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M15 11H18" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
            'device' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><rect x="4.5" y="5" width="15" height="10.5" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M8.5 19H15.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M10.5 15.5L10 19" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M13.5 15.5L14 19" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
            'tax' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M7 4.5H17V19.5H7C5.89543 19.5 5 18.6046 5 17.5V6.5C5 5.39543 5.89543 4.5 7 4.5Z" stroke="currentColor" stroke-width="1.7"/><path d="M9 9H15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M9 13H15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M9 17H12.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M15.5 6.8L17.8 9.1" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M17.8 6.8L15.5 9.1" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
            'gift' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4.5 10.5H19.5V19.5H4.5V10.5Z" stroke="currentColor" stroke-width="1.7"/><path d="M12 10.5V19.5" stroke="currentColor" stroke-width="1.7"/><path d="M3.5 7.5C3.5 6.67157 4.17157 6 5 6H19C19.8284 6 20.5 6.67157 20.5 7.5V10.5H3.5V7.5Z" stroke="currentColor" stroke-width="1.7"/><path d="M12 6C10.3431 6 9 4.99264 9 3.75C9 2.7835 9.7835 2 10.75 2C11.5272 2 12.1348 2.44192 12.5 3.2C12.8652 2.44192 13.4728 2 14.25 2C15.2165 2 16 2.7835 16 3.75C16 4.99264 14.6569 6 13 6H12Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>',
            'tag' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4.5 10.2V5.5C4.5 4.94772 4.94772 4.5 5.5 4.5H10.2C10.4652 4.5 10.7196 4.60536 10.9071 4.79289L18.7071 12.5929C19.0976 12.9834 19.0976 13.6166 18.7071 14.0071L14.0071 18.7071C13.6166 19.0976 12.9834 19.0976 12.5929 18.7071L4.79289 10.9071C4.60536 10.7196 4.5 10.4652 4.5 10.2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><circle cx="8.25" cy="8.25" r="1.25" fill="currentColor"/></svg>',
        ];

        return $icons[$iconName] ?? $icons['tag'];
    }
}
