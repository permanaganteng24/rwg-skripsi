<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Blade;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_report')
                ->label('Download Report')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date')
                        ->required()
                        ->default(now()->startOfMonth()),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date')
                        ->required()
                        ->default(now()->endOfMonth()),
                ])
                ->action(function (array $data) {
                    $orders = Order::whereBetween('created_at', [
                        $data['start_date'] . ' 00:00:00',
                        $data['end_date'] . ' 23:59:59',
                    ])->orderBy('created_at', 'desc')->get();

                    $pdf = Pdf::loadHtml(
                        Blade::render('pdf.orders', [
                            'orders' => $orders,
                            'startDate' => $data['start_date'],
                            'endDate' => $data['end_date'],
                        ])
                    )->setPaper('a4', 'landscape');

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'rekap-penjualan-' . $data['start_date'] . '_' . $data['end_date'] . '.pdf');
                }),

            Actions\CreateAction::make(),
        ];
    }
}
