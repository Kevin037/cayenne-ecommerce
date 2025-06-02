<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Filament\Resources\TransactionResource\RelationManagers\DeliveryRelationManager;
use App\Filament\Resources\TransactionResource\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\TransactionResource\RelationManagers\PaymentRelationManager;
use App\Models\Customer;
use App\Models\Promotion;
use App\Models\Transaction;
use App\Models\User;
use Filament\Tables\Filters\Filter;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Label;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $navigationIcon = 'heroicon-c-shopping-bag';
    protected static ?string $navigationGroup = 'Processes';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            DateTimePicker::make('created_at')->timezone('Asia/Jakarta')->readOnly(),
            TextInput::make('no')->readOnly(),
            TextInput::make('name')->readOnly(),
            TextInput::make('email')->readOnly(),
            TextInput::make('phone')->readOnly(),
            TextInput::make('subtotal')->readOnly(),
            TextInput::make('discount')->readOnly(),
            TextInput::make('total')->readOnly(),
            Select::make('promotion_id')
            ->label('Promotion')
            ->options(Promotion::get_available(true))->disabled(),
            Select::make('status')
            ->options(Transaction::get_status_options())
            ->searchable()->required(),
            DateTimePicker::make('updated_at')->timezone('Asia/Jakarta')->readOnly(),
            Select::make('updated_by')->label('Diubah Oleh')
            ->options(User::all()->pluck('name','id'))
            ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->searchable(),
                TextColumn::make('created_at')->sortable(),
                ViewColumn::make('status')->view('filament.tables.columns.status'),
                TextColumn::make('customer_name'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('subtotal')->money('IDR')->sortable(),
                TextColumn::make('discount')->money('IDR')->sortable(),
                TextColumn::make('total')->money('IDR')->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                ->options(Transaction::get_status_options()),
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Whatsapp')->icon('heroicon-o-chat-bubble-oval-left')
                ->url(fn (Model $record): string => 
                URL('https://wa.me/'.$record->phone.'?text=Halo '.$record->name.' ('.$record->no.')'))
                ->openUrlInNewTab(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
            DeliveryRelationManager::class,
            PaymentRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
