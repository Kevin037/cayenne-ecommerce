<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $check_sku = Product::check_duplicate($this->data['sku'], "sku", $this->data['id']);
        $duplicate = (!$check_sku) ? "SKU" : "";

        if ($duplicate == "") {
            Notification::make()
            ->title('Saved')
            ->success()
            ->send();
        } else {
            Notification::make()
            ->title('Saving Data Failed')
            ->danger()
            ->body($duplicate.' tidak boleh duplikat dengan data lainnya.')
            ->send();
            $data['sku'] = $record['sku'];
            $this->halt();
        }
        $record->update($data);
        return $record;
    }
        protected function getSavedNotification(): ?Notification
    {
        return null;
    }
}
