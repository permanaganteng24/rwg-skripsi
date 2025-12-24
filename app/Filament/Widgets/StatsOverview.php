<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');
        
        $newOrders = Order::where('order_status', 'new')->count();
        
        $totalDiscount = Order::sum('discount_amount');

        return [
            Stat::make('Total Pemasukan (Paid)', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Pemasukan bersih dari pesanan lunas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17]) 
                ->color('success'),

            Stat::make('Pesanan Baru', $newOrders)
                ->description('Pesanan yang perlu diproses')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Total Diskon Diberikan', 'Rp ' . number_format($totalDiscount, 0, ',', '.'))
                ->description('Efisiensi penggunaan kupon')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),
        ];
    }
}