<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HelpCenterResource\Pages;
use App\Filament\Resources\HelpCenterResource\RelationManagers;
use App\Models\HelpCenter;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HelpCenterResource extends Resource
{
    protected static ?string $model = HelpCenter::class;

    protected static ?string $navigationLabel = 'Pusat Bantuan';
    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-down-left';
    protected static ?string $navigationGroup = 'Masters';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('url_title')->required(),
                RichEditor::make('description')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->sortable(),
                TextColumn::make('name')->sortable(),
                TextColumn::make('url_title')->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHelpCenters::route('/'),
            'create' => Pages\CreateHelpCenter::route('/create'),
            'edit' => Pages\EditHelpCenter::route('/{record}/edit'),
        ];
    }
}
