<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\View\View;
use PHPUnit\Metadata\Group;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // // Product Image
                // ImageColumn::make('images.image')
                //     ->label('Image')
                //     ->disk('public')
                //     ->square(),

                // Product Name
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                // Category
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                // Brand
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),

                // Price
                TextColumn::make('price')
                    ->label('Price')
                    ->money('NPR')
                    ->sortable(),

                // Discount
                TextColumn::make('discount_value')
                    ->label('Discount')
                    ->formatStateUsing(fn ($state, $record) =>
                        $state
                            ? $state . ($record->discount_type === 'percentage' ? '%' : ' NPR')
                            : 'No Discount'
                    ),

                // Stock Status
                IconColumn::make('in_stock')
                    ->label('In Stock')
                    ->boolean(),

                // Active Status
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                // Featured
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                // On Sale
                IconColumn::make('on_sale')
                    ->label('On Sale')
                    ->boolean(),

                // Created At
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([

                SelectFilter::make('category')
                    ->relationship('category', 'name'),

                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),

                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                TernaryFilter::make('in_stock')
                    ->label('Stock Status'),

                TernaryFilter::make('is_featured')
                    ->label('Featured'),

                TernaryFilter::make('on_sale')
                    ->label('On Sale'),

            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
