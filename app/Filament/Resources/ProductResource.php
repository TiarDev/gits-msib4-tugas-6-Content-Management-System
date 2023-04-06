<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $modelLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('name')
                    ->reactive()
                    ->afterStateUpdated(function (\Closure $set, $state) {
                    $set('slug', Str::slug($state));
                    })
                    ->label('Nama Kategori')
                    ->placeholder('Nama Kategori'),
                    TextInput::make('slug')
                    ->placeholder('Slug')
                    ->disabled(),
                    Textarea::make('description')->label('Description')
                    ->placeholder('Description')
                    ->nullable(),
                    TextInput::make('price')
                    ->numeric()
                    ->label('Harga'),
                    Select::make('category_id')
                    ->label('Kategori ID')
                    ->searchable()
                    ->options(Category::query()->pluck('name','id')),
                    FileUpload::make('thumbnail')->label('Gambar Produk'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Produk'),
                TextColumn::make('slug')->label('Slug'),
                TextColumn::make('description')->label('Deskripsi'),
                TextColumn::make('price')->label('Harga')
                ->money('idr',','),
                TextColumn::make('category.name')->label('Kategori ID'),
                ImageColumn::make('thumbnail')->label('Gambar Produk')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
