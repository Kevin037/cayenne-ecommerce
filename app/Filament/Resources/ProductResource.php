<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\ProductTypesRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\ProductTypeStocksRelationManager;
use App\Filament\Resources\TypesRelationManagerResource\RelationManagers\TypeDetailsRelationManager;
use App\Models\Product;
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
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ViewColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Masters';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('sku')->required(),
                TextInput::make('tagline'),
                Select::make('product_category_id')
                ->label('Product Category')
                ->options(ProductCategory::get_list_children(true))
                ->searchable()->required(),
                RichEditor::make('description')
                ->disableToolbarButtons([
                    'blockquote',
                    'attachFiles',
                ]),
                TextInput::make('ordering')->numeric(),
                TextInput::make('url_title'),
                TextInput::make('stock')->numeric(),
                TextInput::make('price')->label("Harga (Rp)")->numeric()->required(),
                TextInput::make('hpp')->label("HPP (Rp)")->numeric(),
                TextInput::make('weight')->label("Berat (g)")->numeric()->required(),
                Toggle::make('active'),
                Toggle::make('recommendation'),
                TextInput::make('seo_page_title'),
                Textarea::make('seo_meta_description')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('created_at')->sortable(),
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('sku')->searchable(),
            TextColumn::make('category.name'),
            TextColumn::make('ordering')->sortable(),
            TextColumn::make('url_title')->searchable(),
            TextColumn::make('is_types')->label("Types ?"),
            ViewColumn::make('is_available_stock')->label('Status Stock')->view('filament.tables.columns.stock_status'),
            TextColumn::make('stock'),
            TextColumn::make('price')->money('IDR')->sortable(),
            ToggleColumn::make('active'),
            ToggleColumn::make('recommendation'),
        ])
        ->defaultSort('created_at', 'desc')
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
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
            RelationManagers\PhotosRelationManager::class,
            ProductTypesRelationManager::class,
            ProductTypeStocksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
