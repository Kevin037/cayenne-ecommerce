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

class PaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction_id')
            ->columns([
                ViewColumn::make('status')->view('filament.tables.columns.status'),
                TextColumn::make('total_paid')->money('IDR')->label('Nominal Total'),
                TextColumn::make('expiry_time')->label('Batas Waktu Pembayaran'),
                TextColumn::make('midtrans_url')->label('URL Pembayaran'),
                TextColumn::make('paid_at')->label('Waktu Pembayaran'),
            ]);
    }
}
