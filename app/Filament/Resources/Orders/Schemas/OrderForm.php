<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Group::make()
                    ->schema([

                        // Order Information
                        Section::make('Order Information')
                            ->schema([

                              Select::make('user_id')
                                    ->label('Customer')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $user = \App\Models\User::find($state);

                                        $set('customer_name', $user?->name);
                                        $set('email', $user?->email);
                                        $set('phone', $user?->phone);
                                    }),

                                TextInput::make('customer_name')
                                    ->label('Customer Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->required()
                                    ->email()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label('Phone')
                                    ->required()
                                    ->tel()
                                    ->maxLength(20),

                                TextInput::make('address')
                                    ->label('Shipping Address')
                                    ->required()
                                    ->maxLength(255),
                                
                                TextInput::make('city')
                                    ->label('City')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('province')
                                    ->label('State/Province')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('country')
                                    ->label('Country')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('zip_code')
                                    ->label('ZIP/Postal Code')
                                    ->required()
                                    ->numeric()
                                    ->maxLength(20),
                                    
                                Select::make('payment_method')
                                    ->label('Payment Method')
                                    ->options([
                                        'cod' => 'Cash on Delivery',
                                        'paypal' => 'PayPal',
                                        'bank_transfer' => 'Bank Transfer',
                                    ])
                                    ->required(),

                                Select::make('payment_status')
                                    ->label('Payment Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'failed' => 'Failed',
                                    ])
                                    ->required()
                                    ->default('pending'),

                                ToggleButtons::make('status')
                                    ->label('Order Status')
                                    ->options([
                                        'new' => 'New',
                                        'processing' => 'Processing',
                                        'shipped' => 'Shipped',
                                        'delivered' => 'Delivered',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->default('new')
                                    ->inline()
                                    ->colors([
                                        'new' => 'info',
                                        'processing' => 'warning',
                                        'shipped' => 'success',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                    ])
                                    ->icons([
                                        'new' => 'heroicon-m-sparkles',
                                        'processing' => 'heroicon-m-arrow-path',
                                        'shipped' => 'heroicon-m-truck',
                                        'delivered' => 'heroicon-m-check-badge',
                                        'cancelled' => 'heroicon-m-x-circle',
                                    ]),

                                Textarea::make('notes')
                                    ->label('Order Note')
                                    ->columnSpanFull()
                                    ->placeholder('Enter additional notes or instructions...'),

                            ])
                            ->columns(2),

                        // Order Items
                        Section::make('Order Items')
                            ->live()
                            ->schema([

                                Repeater::make('items')
                                    ->label('Order Items')
                                    ->relationship()
                                    ->live()

                                    ->schema([

                                        Select::make('product_id')
                                            ->label('Product')
                                            ->relationship('product', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->distinct()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->live()
                                            ->columnSpan(4)
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {

                                                $product = Product::find($state);

                                                $price = (float) ($product?->price ?? 0);

                                                $set('price', $price);
                                                $set('discount', 0);
                                                $set('unit_amount', $price);

                                                $quantity = (int) ($get('quantity') ?? 1);

                                                $set('total_amount', $price * $quantity);
                                            }),

                                        TextInput::make('quantity')
                                            ->label('Quantity')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->default(1)
                                            ->live(debounce: 500)
                                            ->columnSpan(2)
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {

                                                $unitAmount = (float) ($get('unit_amount') ?? 0);

                                                $quantity = (int) ($state ?? 1);

                                                $set('total_amount', $unitAmount * $quantity);
                                            }),

                                        TextInput::make('price')
                                            ->label('Original Price')
                                            ->numeric()
                                            ->prefix('NPR')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(2),

                                        TextInput::make('discount')
                                            ->label('Discount')
                                            ->numeric()
                                            ->prefix('NPR')
                                            ->default(0)
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(2),

                                        // TextInput::make('unit_amount')
                                        //     ->label('Unit Amount')
                                        //     ->numeric()
                                        //     ->prefix('NPR')
                                        //     ->required()
                                        //     ->disabled()
                                        //     ->dehydrated()
                                        //     ->columnSpan(2),

                                        TextInput::make('total_amount')
                                            ->label('Total Amount')
                                            ->numeric()
                                            ->prefix('NPR')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(2),

                                    ])
                                    ->columns(12)
                                    ->defaultItems(1)
                                    ->addActionLabel('Add Product')
                                    ->reorderable()
                                    ->columnSpanFull(),

                                Placeholder::make('Total')
                                    ->label('Total Amount')
                                    ->content(function (Get $get, Set $set) {
                                        $total = 0;

                                        if (!$repeaters = $get('items')) {
                                            return $total;
                                        }

                                        foreach ($repeaters as $key => $repeater) {
                                            $total += $get("items.{$key}.total_amount") ?? 0;
                                        }

                                        $set('subtotal', $total);
                                        $set('total_amount', $total);
                                        

                                        return 'NPR ' . number_format($total, 2);
                                    }),

                                Hidden::make('subtotal')
                                    ->default(0),

                                Hidden::make('total_amount')
                                    ->default(0),

                            ]),
                        
                    ])
                    ->columnSpanFull(),

            ]);
    }
}
