<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function beforeCreate(): void
    {
        logger($this->record);
        $check_sku = Product::check_duplicate($this->data['sku'], "sku");
        $duplicate = (!$check_sku) ? "SKU" : "";

        if ($duplicate != "") {
            Notification::make()
            ->title('Saving Data Failed')
            ->danger()
            ->body($duplicate.' tidak boleh duplikat dengan data lainnya.')
            ->send();

            $this->halt();
        }
    }
}
