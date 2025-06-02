<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
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

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-c-presentation-chart-bar';
    protected static ?string $navigationGroup = 'Masters';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('name')
                ->label("name")
                ->options([ 
                    'home' => 'Home',
                ])
                ->searchable()->required(),
                FileUpload::make('photo')->label('Photo')->required(fn ($record): bool => ! $record),
                Placeholder::make('Image')->label('Photo Preview')
                ->content(function ($record): HtmlString {
                    if ($record->photo != "") {
                        return new HtmlString("<img src= '" . $record->photo . "' alt='No-image')>");
                    }
                })->hidden(fn ($record): bool => ! $record),
                FileUpload::make('mobile_photo')->required(fn ($record): bool => ! $record),
                Placeholder::make('Image')->label('Mobile Photo Preview')
                ->content(function ($record): HtmlString {
                    if ($record->mobile_photo != "") {
                        return new HtmlString("<img src= '" . $record->mobile_photo . "' alt='No-image')>");
                    }
                })->hidden(fn ($record): bool => ! $record),
                RichEditor::make('description'),
                TextInput::make('tagline'),
                TextInput::make('ordering')->required(),
                Textarea::make('target_url'),
                Toggle::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('created_at')->sortable(),
            SelectColumn::make('name')
            ->options([
                'home' => 'Home',
            ])->selectablePlaceholder(false),
            TextColumn::make('ordering')->sortable(),
            ImageColumn::make('photo'),
            ImageColumn::make('mobile_photo'),
            TextColumn::make('target_url'),
            ToggleColumn::make('active'),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()->after(function (Banner $record) {
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
