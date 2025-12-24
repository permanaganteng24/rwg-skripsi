<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Riwayat Transaksi';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Order ID')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('order_status')
                    ->label('Status Pesanan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'waiting_quote' => 'danger',
                        'waiting_payment' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->actions([
                Action::make('view')
                    ->label('Lihat Invoice')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn ($record) => route('invoice.download', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([]);
    }
}
