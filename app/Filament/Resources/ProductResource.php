<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder; 

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Shop Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Product Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Product::class, 'slug', ignoreRecord: true),

                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Section::make('Images')
                            ->schema([
                                Forms\Components\Repeater::make('galleries')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\FileUpload::make('image_url')
                                            ->label('Image')
                                            ->image()
                                            ->disk('public')
                                            ->visibility('public')
                                            ->directory('products')
                                            ->required(),
                                            
                                        Forms\Components\Toggle::make('is_thumbnail')
                                            ->label('Main Thumbnail')
                                            ->default(false),
                                    ])
                                    ->grid(2)
                                    ->defaultItems(1)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make('Pricing & Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp'),
                                
                                Forms\Components\TextInput::make('stock')
                                    ->required()
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\Select::make('availability')
                                    ->options([
                                        'ready' => 'Ready Stock',
                                        'pre_order' => 'Pre Order',
                                        'out_of_stock' => 'Out of Stock',
                                    ])
                                    ->default('ready')
                                    ->required(),
                            ]),

                        Section::make('Specifications')
                            ->schema([
                                Forms\Components\TextInput::make('weight_kg')
                                    ->label('Weight (kg)')
                                    ->required()
                                    ->numeric(),
                                
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('length_cm')->label('Length')->numeric(),
                                        Forms\Components\TextInput::make('width_cm')->label('Width')->numeric(),
                                        Forms\Components\TextInput::make('height_cm')->label('Height')->numeric(),
                                    ]),

                                Forms\Components\TextInput::make('material'),
                                Forms\Components\TextInput::make('finishing'),
                            ]),

                        Section::make('Organization')
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),
                                    
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Visible to Customer')
                                    ->default(true),
                                    
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Product')
                                    ->helperText('Show in Homepage Slider'),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->width(64)
                    ->height(64)
                    ->extraImgAttributes(['style' => 'object-fit:cover;border-radius:6px;'])
                    ->getStateUsing(fn (Product $record): ?string => $record->galleries->firstWhere('is_thumbnail', true)?->image_url
                        ?? $record->galleries->first()?->image_url),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Product $record): string => Str::limit(strip_tags($record->description ?? ''), 50)),

                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn (string $state): string => $state > 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('availability')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ready' => 'success',
                        'pre_order' => 'warning',
                        'out_of_stock' => 'danger',
                    }),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('availability'),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['galleries', 'categories']);
    }
}