<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $title = 'Alamat Pengiriman';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Label Alamat')
                    ->placeholder('Contoh: Rumah, Kantor')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('recipient_name')
                    ->label('Penerima')
                    ->required(),
                
                Forms\Components\TextInput::make('phone')
                    ->label('No HP')
                    ->tel()
                    ->required(),

                Forms\Components\Textarea::make('address_line')
                    ->label('Alamat Lengkap')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('city')->required(),
                        Forms\Components\TextInput::make('district')->required(),
                        Forms\Components\TextInput::make('province')->required(),
                        Forms\Components\TextInput::make('postal_code')->numeric(),
                    ]),
                
                Forms\Components\Toggle::make('is_default')
                    ->label('Jadikan Alamat Utama'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('recipient_name')
                    ->description(fn ($record) => $record->phone),
                
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota'),

                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->label('Utama'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}