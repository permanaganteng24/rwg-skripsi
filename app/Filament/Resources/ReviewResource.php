<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Shop Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Customer Reviews';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approved', false)->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Review Content')
                            ->schema([
                                // Info User & Produk 
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('Customer')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->label('Product')
                                    ->disabled()
                                    ->dehydrated(false),

                                // Rating & Komentar
                                Forms\Components\TextInput::make('rating')
                                    ->label('Rating (1-5)')
                                    ->numeric()
                                    ->disabled(),

                                Forms\Components\Textarea::make('comment')
                                    ->label('Comment')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->disabled(),
                            ])->columns(2),
                        
                        // Foto Review 
                        Forms\Components\Section::make('Customer Photos')
                            ->schema([
                                Forms\Components\Repeater::make('images')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\FileUpload::make('image_url')
                                            ->image()
                                            ->disabled()
                                            ->openable()
                                            ->downloadable(),
                                    ])
                                    ->grid(4)
                                    ->addable(false) 
                                    ->deletable(false) 
                                    ->label(''),
                            ])
                            ->collapsible(),
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Moderation')
                            ->schema([
                                // Tombol Approve / Reject
                                Forms\Components\Toggle::make('is_approved')
                                    ->label('Approve Review')
                                    ->helperText('Enable this to show review on the website.')
                                    ->onColor('success')
                                    ->offColor('danger'),
                                
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Submitted At')
                                    ->content(fn (Review $record): string => $record->created_at->diffForHumans()),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y, H:i') 
                    ->sortable()
                    ->label('Date'),

                Tables\Columns\ImageColumn::make('images.image_url')
                    ->label('Photos')
                    ->circular()
                    ->stacked()
                    ->limit(3)  
                    ->limitedRemainingText(),

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('Customer'),

                Tables\Columns\TextColumn::make('product.name')
                    ->searchable()
                    ->limit(30)
                    ->label('Product'),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (string $state): string => str_repeat('⭐', $state))
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean()
                    ->label('Approved')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Status')
                    ->trueLabel('Approved Reviews')
                    ->falseLabel('Pending Reviews'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Review $record) => !$record->is_approved)
                    ->action(fn (Review $record) => $record->update(['is_approved' => true])),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'product']); 
    }
}
