<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
use App\Models\ProductCategory;
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
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Support\Facades\Storage;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationLabel = 'Kategori Produk';
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationGroup = 'Masters';

    public static function form(Form $form): Form
    {
        $url = str_replace(ProductCategoryResource::getUrl(),"",url()->current());
        $id = ($url == '/create') ? null : str_replace('/','',str_replace('edit','',$url));
        $parent_option = ($id != null) ? ProductCategory::get_available_options($id) : ProductCategory::get_available_options();
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
                RichEditor::make('description'),
                RichEditor::make('tagline'),
                TextInput::make('ordering'),
                TextInput::make('url_title'),
                Select::make('parent_id')
                ->label('Parent Category')
                ->options($parent_option)
                ->searchable(),
                Toggle::make('active'),
                TextInput::make('seo_page_title'),
                RichEditor::make('seo_meta_description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('created_at')->sortable(),
            TextColumn::make('name')->searchable(),
            TextColumn::make('url_title')->searchable(),
            ImageColumn::make('photo'),
            TextColumn::make('ordering')->sortable(),
            ToggleColumn::make('active'),
            TextColumn::make('parent_name')->sortable(),
        ])
        ->defaultSort('created_at', 'desc')
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()->after(function (ProductCategory $record) {
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
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
