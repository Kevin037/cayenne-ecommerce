<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryRelationManager extends RelationManager
{
    protected static string $relationship = 'delivery';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction_id')
            ->columns([
                ViewColumn::make('id')->label('Alamat')
                ->view('filament.tables.columns.address'),
                TextColumn::make('weight')->label('Berat (gr)'),
                TextInputColumn::make('no')->label('No Resi')->disabled(fn (): bool => $this->getOwnerRecord()->status != "sending")
                ->afterStateUpdated(function ($record, $state) {
                    dispatch(new \App\Jobs\SendEmailUpdateResi($this->getOwnerRecord()));
                }),
                TextColumn::make('estimated_delivery')->label('Estmasi Kirim (hari)'),
                TextColumn::make('courier_name')->label('Kurir'),
                TextColumn::make('shipping_cost')->label('Biaya Kirim')->money('IDR'),
            ])
            ->filters([
                //
            ])->actions([
            ]);
    }
}
