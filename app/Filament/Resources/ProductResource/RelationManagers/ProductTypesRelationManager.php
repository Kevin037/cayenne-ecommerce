<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Filament\Resources\ProductResource;
use App\Models\ProductType;
use App\Models\ProductTypeStock;
use App\Models\Type;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
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

class ProductTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'types';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                Select::make('type_id')
                ->label('Type')
                ->options(Type::all()->pluck('name', 'id'))
                ->searchable()->required(),
                FileUpload::make('photo')->label('Photo'),
                Placeholder::make('Image')->label('Photo Preview')
                ->content(function ($record): HtmlString {
                    if ($record->photo != "") {
                        return new HtmlString("<img src= '" . $record->photo . "' alt='No-image')>");
                    }
                })->hidden(fn ($record): bool => ! $record),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                TextColumn::make('name'),
                SelectColumn::make('type_id')
                ->options(Type::all()->pluck('name', 'id'))
                ->disabled(),
                ImageColumn::make('photo'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->after(function (ProductType $productType) {
                    $arr = [];
                    foreach ($this->getOwnerRecord()->types as $key => $value) {
                        if ($value->type_id != $productType->type_id) {
                            $arr[] = $value->id;
                        }
                    }
                    if (count($arr) > 0) {
                        foreach ($arr as $val) {
                            $validated['product_type_ids'] = json_encode([$productType->id,$val]);
                            $validated['stock'] = 0;
                            $validated['product_id'] = $this->getOwnerRecord()->id;
                            $created = ProductTypeStock::create($validated);
                            $created->save();
                        }
                    } else {
                        foreach ($this->getOwnerRecord()->types as $val) {
                            $validated['product_type_ids'] = json_encode([$val->id]);
                            $validated['stock'] = 0;
                            $validated['product_id'] = $this->getOwnerRecord()->id;
                            $created = ProductTypeStock::create($validated);
                            $created->save();
                        }
                    }
                })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->before(function (ProductType $productType) {
                    foreach ($this->getOwnerRecord()->stocks as $key => $value) {
                        if (in_array($productType->id,json_decode($value->product_type_ids))) {
                            $value->delete();
                        }
                    }
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->before(function ($livewire) {
                        foreach ($livewire->getSelectedTableRecords() as $productType) {
                            foreach ($this->getOwnerRecord()->stocks as $value) {
                                if (in_array($productType->id,json_decode($value->product_type_ids))) {
                                    $value->delete();
                                }
                            }
                        }
                    })
                ]),
            ])->defaultSort('type_id');
    }
}
