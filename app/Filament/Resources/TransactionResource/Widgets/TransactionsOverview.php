<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\General;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $nett_profit = "Rp".number_format(General::get_monthly_nett_profit_transaction(),0,',','.');
        $nett_profit_progress = General::get_monthly_progress_nett_profit_transaction();
        $total_transaction = General::get_monthly_total_transaction();
        $gross_profit = "Rp".number_format(General::get_monthly_gross_profit_transaction(),0,',','.');
        $gross_profit_progress = General::get_monthly_progress_gross_profit_transaction();
        $product_sale = General::get_monthly_product_sale_transaction();
        $customers = General::get_monthly_customer();
        $charts = General::get_monthly_chart_transaction();
        return [
            Stat::make('Omset', $gross_profit)
            ->description('Total pendapatan kotor bulan ini')
            ->descriptionIcon('heroicon-o-currency-dollar')
            ->chart($gross_profit_progress)
            ->color('success'),
            Stat::make('Profit', $nett_profit)
            ->description('Total pendapatan bersih bulan ini')
            ->descriptionIcon('heroicon-o-currency-dollar')
            ->chart($nett_profit_progress)
            ->color('success'),
            Stat::make('Total Transaksi', $total_transaction)
            ->description('Jumlah Transaksi bulan ini')
            ->descriptionIcon('heroicon-c-shopping-bag')
            ->color('info'),
            Stat::make('Produk Terjual', $product_sale)
            ->description('Jumlah Produk Terjual bulan ini')
            ->descriptionIcon('heroicon-o-rectangle-stack')
            ->color('info'),
            Stat::make('Keranjang', $charts)
            ->description('Jumlah Keranjang aktif bulan ini')
            ->descriptionIcon('heroicon-m-archive-box-arrow-down')
            ->color('warning'),
            Stat::make('Pelanggan Baru', $customers)
            ->description('Jumlah pelanggan baru bulan ini')
            ->descriptionIcon('heroicon-c-user-circle')
            ->color('gray'),
        ];
    }

    public function goto()
    {
        return redirect()->to('/admin/customers');
    }
}
