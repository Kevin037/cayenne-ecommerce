<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction_id')
            ->columns([
                ViewColumn::make('product_id')->label('Photo')
                ->view('filament.tables.columns.product_detail_photo'),
                TextColumn::make('product_name'),
                TextColumn::make('qty'),
                TextColumn::make('price')->money('IDR'),
            ])
            ->filters([
                //
            ]);
    }
}
