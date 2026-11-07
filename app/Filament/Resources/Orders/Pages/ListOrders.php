<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Widgets\OrderStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'new' => Tab::make()->query(fn ($query) => $query->where('status', 'new'))->label('New'),
            'processing' => Tab::make()->query(fn ($query) => $query->where('status', 'processing'))->label('Processing'),
            'shipped' => Tab::make()->query(fn ($query) => $query->where('status', 'shipped'))->label('Shipped'),
            'delivered' => Tab::make()->query(fn ($query) => $query->where('status', 'delivered'))->label('Delivered'),
            'cancelled' => Tab::make()->query(fn ($query) => $query->where('status', 'cancelled'))->label('Cancelled'),
        ];
    }
}
