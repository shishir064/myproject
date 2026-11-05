<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()->live(onBlur: true)->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required(),
                FileUpload::make('image')
                    ->image(),

                Toggle::make('status')
                    ->label('Is Active')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->helperText('Enable or disable this brand.'),

                
            ]);
    }
}
