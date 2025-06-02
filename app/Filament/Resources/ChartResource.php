<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChartResource\Pages;
use App\Filament\Resources\ChartResource\RelationManagers;
use App\Models\Chart;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class ChartResource extends Resource
{
    protected static ?string $model = Chart::class;

    protected static ?string $navigationLabel = 'Keranjang';
    protected static ?string $navigationIcon = 'heroicon-m-archive-box-arrow-down';
    protected static ?string $navigationGroup = 'Processes';

    public static function canCreate(): bool
    {
         return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->sortable(),
                TextColumn::make('customer_name'),
                TextColumn::make('product_name'),
                ViewColumn::make('product_id')->label('Photo')
                ->view('filament.tables.columns.product_detail_photo'),
                TextColumn::make('qty')->sortable(),
                TextColumn::make('price')->money('IDR')->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from')->default(date("Y-m-d",strtotime("-1 week +1 day"))),
                    DatePicker::make('created_until')->default(now()),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
            ])->actions([
                Tables\Actions\Action::make('Whatsapp')->icon('heroicon-o-chat-bubble-oval-left')
                ->url(fn (Model $record): string => 
                URL('https://wa.me/'.$record->customer->phone.'?text=Halo '.$record->customer->name))
                ->openUrlInNewTab(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCharts::route('/'),
        ];
    }
}
