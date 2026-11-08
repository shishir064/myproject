<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Customer Name')
                ->label('Customer Name')
                ->sortable()
                ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('order_number')
                    ->label('Order Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->sortable()
                    ->searchable(), 

                SelectColumn::make('status')
                    ->label('Order Status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
