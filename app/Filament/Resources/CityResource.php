<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use App\Models\Province;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $navigationLabel = 'Kota';
    protected static ?string $model = City::class;
    protected static ?string $navigationGroup = 'Masters';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
         return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('province.name')->sortable()->searchable(),
                TextColumn::make('type')->searchable(),
                TextColumn::make('postal_code')->sortable()->searchable(),
                TextColumn::make('created_at')
            ])
            ->filters([
                SelectFilter::make('province_id')->label('Provinsi')
                ->options(Province::all()->pluck('name', 'id')),
                SelectFilter::make('type')
                ->options([
                    'Kabupaten' => 'Kabupaten',
                    'Kota' => 'Kota'
                ])
            ])->headerActions([
                Tables\Actions\Action::make('Update')->icon('heroicon-m-clock')->after(function () {
                    $city = new City();
                    $city->store();
                })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
        ];
    }
}
