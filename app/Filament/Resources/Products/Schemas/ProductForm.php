<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // LEFT SIDE
                Group::make()
                    ->schema([

                        // Basic Information
                        Section::make('Basic Information')
                            ->description('Enter product details.')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, $set) => $set('slug', str($state)->slug()))
                                    ,

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Product::class, 'slug', ignoreRecord: true),

                                MarkdownEditor::make('description')
                                    ->fileAttachmentsDirectory('products')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),

                        // Product Images
                            Section::make('Product Images')
                                ->schema([
                                    // FileUpload::make('images')
                                    //     ->label('Upload Product Images')
                                    //     ->image()
                                    //     ->multiple()
                                    //     ->reorderable()
                                    //     ->maxFiles(5)
                                    //     ->imageEditor()
                                    //     ->disk('public')
                                    //     ->directory('products')
                                    //     ->visibility('public')
                                    //     ->columnSpanFull(),
                                    FileUpload::make('images')
                                        ->label('Upload Product Images')
                                        ->image()
                                        ->multiple()
                                        ->reorderable()
                                        ->maxFiles(5)
                                        ->imageEditor(),
                                ]),

                    ])
                    ->columnSpan(2),

                // RIGHT SIDE
                Group::make()
                    ->schema([

                        // Product Pricing
                        Section::make('Product Pricing')
                            ->description('Set product price and discount information.')
                            ->schema([

                                TextInput::make('price')
                                    ->label('Regular Price')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->prefix('NPR')
                                    ->columnSpanFull(),

                                Select::make('discount_type')
                                    ->label('Discount Type')
                                    ->options([
                                        'percentage' => 'Percentage (%)',
                                        'fixed' => 'Fixed Amount (NPR)',
                                    ])
                                    ->placeholder('Select discount type')
                                    ->live(),

                                TextInput::make('discount_value')
                                    ->label('Discount Value')
                                    ->numeric()
                                    ->minValue(0)
                                    ->visible(fn ($get) => filled($get('discount_type')))
                                    ->suffix(fn ($get) =>
                                        $get('discount_type') === 'percentage'
                                            ? '%'
                                            : 'NPR'
                                    ),

                            ]),

                        // Associations
                        Section::make('Associations')
                            ->schema([

                                Select::make('category_id')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship('category', 'name'),

                                Select::make('brand_id')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship('brand', 'name'),

                            ]),

                        // Status
                        Section::make('Status')
                            ->schema([

                                Toggle::make('in_stock')
                                    ->required()
                                    ->default(true),

                                Toggle::make('is_active')
                                    ->required()
                                    ->default(true),

                                Toggle::make('is_featured')
                                    ->required()
                                    ->default(false),

                                Toggle::make('on_sale')
                                    ->required()
                                    ->default(false),

                            ]),

                    ])
                    ->columnSpan(1),

            ])
            ->columns(3);
    }
}
