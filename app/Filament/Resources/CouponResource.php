<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get; 
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Shop Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Detail Kupon')
                ->description('Buat kode promo untuk pelanggan.')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Kode Kupon')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->extraInputAttributes(['style' => 'text-transform: uppercase']) 
                        ->dehydrateStateUsing(fn ($state) => strtoupper($state)) 
                        ->placeholder('CONTOH: MERDEKA45')
                        ->maxLength(255),

                    Forms\Components\Select::make('type')
                        ->label('Tipe Potongan')
                        ->options([
                            'fixed' => 'Nominal Tetap (Rp)',
                            'percent' => 'Persentase (%)',
                        ])
                        ->required()
                        ->default('fixed')
                        ->live()
                        ->native(false),

                    Forms\Components\TextInput::make('value')
                        ->label('Besar Potongan')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->prefix(fn (Get $get) => $get('type') === 'fixed' ? 'Rp' : null)
                        ->suffix(fn (Get $get) => $get('type') === 'percent' ? '%' : null)
                        ->placeholder(fn (Get $get) => $get('type') === 'fixed' ? 'Contoh: 50000' : 'Contoh: 10'),

                    Forms\Components\TextInput::make('min_spend')
                        ->label('Minimal Belanja')
                        ->numeric()
                        ->default(0)
                        ->prefix('Rp')
                        ->helperText('Isi 0 jika tidak ada minimal belanja.'),

                    Forms\Components\DatePicker::make('expiry_date')
                        ->label('Berlaku Sampai')
                        ->native(false)
                        ->displayFormat('d M Y')
                        ->minDate(now()),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger'),
                ])->columns(2),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable() 
                    ->copyMessage('Kode disalin!'),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fixed' => 'info',
                        'percent' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'fixed' => 'Fixed (Rp)',
                        'percent' => 'Percent (%)',
                    }),

                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->sortable()
                    ->formatStateUsing(fn (Coupon $record) => $record->type === 'fixed' 
                        ? 'Rp ' . number_format($record->value, 0, ',', '.') 
                        : $record->value . '%'),

                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Kadaluarsa')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('Selamanya'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                // Filter Aktif/Tidak
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
