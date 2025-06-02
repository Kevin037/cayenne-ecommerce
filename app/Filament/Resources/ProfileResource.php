<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfileResource\Pages;
use App\Filament\Resources\ProfileResource\RelationManagers;
use App\Models\City;
use App\Models\Delivery;
use App\Models\Profile;
use App\Models\Province;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static ?string $navigationLabel = 'Profil';
    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                FileUpload::make('photo')->label('Photo')->required(fn ($record): bool => ! $record),
                Placeholder::make('Image')->label('Photo Preview')
                ->content(function ($record): HtmlString {
                    if ($record->photo != "") {
                        return new HtmlString("<img src= '" . $record->photo . "' alt='No-image')>");
                    }
                })->hidden(fn ($record): bool => ! $record),
                Textarea::make('tagline'),
                TextInput::make('operational_time')->required(),
                TextInput::make('email')->required(),
                TextInput::make('no_telp')->required(),
                RichEditor::make('about')->required(),
                Textarea::make('address')->required(),
                Select::make('province')
                ->label('Provinsi')
                ->options(Province::all()->pluck('name', 'id'))
                ->searchable()->required()->preload()->live(),
                Select::make('city')
                ->label('Kota')
                ->options(fn (Get $get): Collection => City::where('province_id',$get('province'))->pluck('name', 'id'))
                ->searchable()->required(),
                Textarea::make('map'),
                Textarea::make('facebook_pixel'),
                Textarea::make('google_ads'),
                TextInput::make('seo_page_title'),
                Textarea::make('seo_meta_description'),
            ]);
    }

    public static function canCreate(): bool
    {
         $data = Profile::all();
         return (count($data) > 0) ? false: true;
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name'),
            ImageColumn::make('photo'),
            TextColumn::make('tagline'),
            TextColumn::make('operational_time'),
            TextColumn::make('email'),
            TextColumn::make('no_telp'),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()->after(function (Profile $record) {
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
            'index' => Pages\ListProfiles::route('/'),
            'create' => Pages\CreateProfile::route('/create'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }
}
