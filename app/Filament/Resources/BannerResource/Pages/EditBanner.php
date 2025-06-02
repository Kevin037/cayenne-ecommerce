<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($data['photo'] == null) {
            unset($data['photo']);
        } else {
            Storage::disk('public')->delete($record->getRawOriginal('photo'));
        }

        if ($data['mobile_photo'] == null) {
            unset($data['mobile_photo']);
        } else {
            Storage::disk('public')->delete($record->getRawOriginal('mobile_photo'));
        }

        $record->update($data);
        return $record;
    }
}
