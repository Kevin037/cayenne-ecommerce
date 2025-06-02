<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialMediaResource\Pages;
use App\Filament\Resources\SocialMediaResource\RelationManagers;
use App\Models\SocialMedia;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Support\Facades\Storage;

class SocialMediaResource extends Resource
{
    protected static ?string $model = SocialMedia::class;

    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Masters';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('icon')
                ->label("Icon")
                ->options([ 
                    'instagram' => 'Instagram',
                    'tiktok' => 'Tiktok',
                    'fecebook-square' => 'Facebook',
                    'youtube' => 'Youtube',
                    'whatsapp' => 'Whatsapp',
                    'telegram' => 'Telegram',
                    'twitter' => 'Twitter'
                ])
                ->searchable(),
                Textarea::make('url')->required(),
                TextInput::make('ordering')->required(),
                Toggle::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('created_at')->sortable(),
            TextColumn::make('name'),
            SelectColumn::make('icon')->label("Icon")
            ->options([
                'instagram' => 'Instagram',
                'tiktok' => 'Tiktok',
                'facebook-square' => 'Facebook',
                'youtube' => 'Youtube',
                'whatsapp' => 'Whatsapp',
                'telegram' => 'Telegram',
                'twitter' => 'Twitter'
            ]),
            TextColumn::make('url'),
            TextColumn::make('ordering')->sortable(),
            ToggleColumn::make('active'),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()->after(function (SocialMedia $record) {
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
            'index' => Pages\ListSocialMedia::route('/'),
            'create' => Pages\CreateSocialMedia::route('/create'),
            'edit' => Pages\EditSocialMedia::route('/{record}/edit'),
        ];
    }
}
