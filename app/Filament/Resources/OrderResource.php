<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Shop Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Orders';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('order_status', 'waiting_quote')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Order Information')
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Order ID')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('order_status')
                                    ->label('Status')
                                    ->options([
                                        'new' => 'New Order',
                                        'waiting_quote' => 'Waiting Quote (Cargo)',
                                        'waiting_payment' => 'Waiting Payment',
                                        'processing' => 'Processing (Paid)',
                                        'shipped' => 'Shipped',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->default('new'),

                                Forms\Components\Select::make('payment_status')
                                    ->options([
                                        'unpaid' => 'Unpaid',
                                        'paid' => 'Paid',
                                        'failed' => 'Failed',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('shipping_method')
                                    ->label('Metode Pengiriman')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpanFull(),

                            ])->columns(2),

                        Forms\Components\Section::make('Customer Details')
                            ->schema([
                                Forms\Components\TextInput::make('shipping_name')->label('Nama Penerima')->required(),
                                Forms\Components\TextInput::make('shipping_phone')->label('WhatsApp')->required(),
                                Forms\Components\TextInput::make('shipping_email')->label('Email')->email(),
                                Forms\Components\TextInput::make('company_name')->label('Perusahaan (Opsional)'),

                                Forms\Components\Textarea::make('shipping_address')
                                    ->label('Alamat Lengkap')
                                    ->columnSpanFull()
                                    ->required(),

                                Forms\Components\TextInput::make('shipping_city')->label('Kota/Kab'),
                                Forms\Components\TextInput::make('shipping_district')->label('Kecamatan'),
                                Forms\Components\TextInput::make('shipping_province')->label('Provinsi'),
                                Forms\Components\TextInput::make('shipping_postal_code')->label('Kode Pos'),
                            ])->columns(2),
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Cost Calculation')
                            ->schema([
                                Forms\Components\TextInput::make('total_product_price')
                                    ->label('Subtotal Product')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('shipping_price')
                                    ->label('Biaya Ongkir (Real)')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('discount_amount')
                                    ->label('Diskon')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('grand_total')
                                    ->label('Grand Total')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ]),

                        Forms\Components\Section::make('Admin Notes')
                            ->schema([
                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Internal')
                                    ->rows(3),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('shipping_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn(Order $record) => $record->shipping_city ?? '-'),

                Tables\Columns\TextColumn::make('grand_total')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        'failed' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('order_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
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
            ->actions([
                Tables\Actions\Action::make('input_ongkir')
                    ->label('Input Ongkir')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('info')
                    ->visible(fn(Order $record) => $record->order_status === 'waiting_quote')
                    ->form([
                        Forms\Components\TextInput::make('shipping_price')
                            ->label('Biaya Ongkir Real (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan untuk Customer')
                            ->default('Ongkir sudah dihitung, silakan lakukan pembayaran.'),
                    ])
                    ->action(function (Order $record, array $data) {
                        $newGrandTotal = $record->total_product_price + $data['shipping_price'] - $record->discount_amount;

                        $record->update([
                            'shipping_price' => $data['shipping_price'],
                            'grand_total' => $newGrandTotal,
                            'order_status' => 'waiting_payment',
                            'notes' => $data['notes']
                        ]);

                        Notification::make()
                            ->title('Ongkir Disimpan')
                            ->body('Status order berubah menjadi Waiting Payment.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('update_resi')
                    ->label('Kirim Resi')
                    ->icon('heroicon-m-truck')
                    ->color('primary')
                    ->visible(fn(Order $record) => in_array($record->order_status, ['processing', 'shipped']))
                    ->form([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->required(),
                        Forms\Components\TextInput::make('shipping_courier')
                            ->label('Nama Kurir/Ekspedisi')
                            ->default('JNE Trucking'),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'tracking_number' => $data['tracking_number'],
                            'shipping_courier' => $data['shipping_courier'],
                            'order_status' => 'shipped',
                        ]);

                        Notification::make()->title('Resi Berhasil Diupdate')->success()->send();
                    }),
                Action::make('pdf')
                    ->label('Invoice')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn(Order $record) => route('invoice.download', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Export PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                echo Pdf::loadHtml(
                                    Blade::render('pdf.orders', ['orders' => $records])
                                )->stream();
                            }, 'rekap-penjualan-' . date('Y-m-d') . '.pdf');
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['items']); 
    }
}
