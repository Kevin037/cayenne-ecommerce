<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function beforeCreate(): void
    {
        logger($this->record);
        $check_name = ProductCategory::check_duplicate($this->data['name']);
        $duplicate = (!$check_name) ? "Name" : "";

        if ($duplicate != "") {
            Notification::make()
            ->title('Creating Data Failed')
            ->danger()
            ->body($duplicate.' tidak boleh duplikat dengan data lainnya.')
            ->send();

            $this->halt();
        } else {
            Notification::make()
            ->title('Created')
            ->success()
            ->send();
        }
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
}
