<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        $data = $this->data;

        User::where('id', $this->record->user_id)->update([
            'name' => $data['customer_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'Province' => $data['province'],
            'zip_code' => $data['zip_code'],
            'country' => $data['country'],
        ]);
    }
}
