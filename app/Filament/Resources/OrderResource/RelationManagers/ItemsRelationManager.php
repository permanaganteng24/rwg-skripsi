<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items'; 

    protected static ?string $title = 'Produk yang Dibeli';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')->required(),
                Forms\Components\TextInput::make('quantity')->required(),
                Forms\Components\TextInput::make('price_per_unit')->required(),
                Forms\Components\TextInput::make('subtotal')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                Tables\Columns\ImageColumn::make('product.galleries.image_url')
                    ->label('Foto')
                    ->circular() 
                    ->stacked() 
                    ->limit(1) 
                    ->defaultImageUrl(url('/assets/image/no-image.png')), 
                    
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->weight('bold')
                    ->wrap(), 

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('price_per_unit')
                    ->label('Harga Satuan')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->weight('bold')
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}