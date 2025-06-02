<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Filament\Resources\PromotionResource\Pages\EditPromotion;
use App\Filament\Resources\PromotionResource\RelationManagers;
use App\Models\Product;
use App\Models\Promotion;
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
use Illuminate\Support\Facades\Redis;
use Filament\Forms\Components\CheckboxList;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationLabel = 'Promosi';
    protected static ?string $navigationIcon = 'heroicon-c-document-currency-dollar';
    protected static ?string $navigationGroup = 'Masters';

    public static function form(Form $form): Form
    {
        $url = str_replace(PromotionResource::getUrl(),"",url()->current());
        $id = ($url == '/create') ? null : str_replace('/','',str_replace('edit','',$url));
        return $form
        ->schema([
            TextInput::make('name')->required(),
            Select::make('type')
            ->options([ 
                'voucher' => 'Voucher',
                'product' => 'Product',
            ])->searchable()->required()->disabledOn('edit'),
            TextInput::make('coupon_code')->required()
            ->hidden(fn ($record): bool => ! $record)
            ->visible(fn ($record): bool => $record->type == "voucher"),
            CheckboxList::make('product_ids')
            ->options(
                Product::get_options($id)
            )
            ->bulkToggleable()->required()->searchable()->columns(2)->gridDirection('row')
            ->hidden(fn ($record): bool => ! $record)
            ->visible(fn ($record): bool => $record->type == "product"),
            Select::make('discount_type')
            ->options([ 
                'nominal' => 'Nominal',
                'percentage' => 'Percentage',
            ])
            ->searchable()->required()->disabledOn('edit'),
            TextInput::make('discount_percentage')->required()
            ->hidden(fn ($record): bool => ! $record)
            ->visible(fn ($record): bool => $record->discount_type == "percentage"),
            TextInput::make('discount_nominal')->required()
            ->hidden(fn ($record): bool => ! $record)
            ->visible(fn ($record): bool => $record->discount_type == "nominal"),
            FileUpload::make('photo')->label('Photo'),
            Placeholder::make('Image')->label('Photo Preview')
            ->content(function ($record): HtmlString {
                if ($record->photo != "" || $record->photo != NULL ) {
                    return new HtmlString("<img src= '" . $record->photo . "' alt='No-image')>");
                }
            })->hidden(fn ($record): bool => ! $record),
            DateTimePicker::make('start_date')->timezone('Asia/Jakarta')->required(),
            DateTimePicker::make('end_date')->timezone('Asia/Jakarta')->required(),
            Toggle::make('active')->hidden(fn ($record): bool => ! $record),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->sortable(),
                TextColumn::make('name')->searchable(),
                SelectColumn::make('type')
                ->options([ 
                    'voucher' => 'Voucher',
                    'product' => 'Product',
                ])->selectablePlaceholder(false),
                SelectColumn::make('discount_type')
                ->options([ 
                    'nominal' => 'Nominal',
                    'percentage' => 'Percentage',
                ])->selectablePlaceholder(false),
                ImageColumn::make('photo'),
                ToggleColumn::make('active'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->after(function (Promotion $record) {
                    Storage::disk('public')->delete($record->getRawOriginal('photo'));
                }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
