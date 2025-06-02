<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $check_name = ProductCategory::check_duplicate($this->data['name'], $this->data['id']);
        $duplicate = (!$check_name) ? "Name" : "";

        if ($duplicate != "") {
            Notification::make()
            ->title('Saving Data Failed')
            ->danger()
            ->body($duplicate.' tidak boleh duplikat dengan data lainnya.')
            ->send();
            $data['name'] = $record['name'];
        } else {
            Notification::make()
            ->title('Saved')
            ->success()
            ->send();
        }

        if ($data['photo'] == null) {
            unset($data['photo']);
        } else {
            Storage::disk('public')->delete($record->getRawOriginal('photo'));
        }
        $record->update($data);
        return $record;
    }
}
