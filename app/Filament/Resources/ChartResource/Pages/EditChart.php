<?php

namespace App\Filament\Resources\ChartResource\Pages;

use App\Filament\Resources\ChartResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChart extends EditRecord
{
    protected static string $resource = ChartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
