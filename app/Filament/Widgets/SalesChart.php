<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan (30 Hari Terakhir)';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $rows = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(grand_total) as total')
            )
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->where('created_at', '<=', now()->endOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $labels = $rows->pluck('date')->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M'))->values()->toArray();
        $data   = $rows->pluck('total')->map(fn ($v) => (float) $v)->values()->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Total Penjualan',
                    'data'            => $data,
                    'backgroundColor' => '#6B4226',
                    'borderColor'     => '#6B4226',
                    'fill'            => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}