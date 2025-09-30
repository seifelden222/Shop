<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Pages\ViewProduct;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->description('Basic product details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Product Name'),
                        
                        Textarea::make('description')
                            ->required()
                            ->columnSpanFull()
                            ->label('Description'),
                        
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->label('Price'),
                        
                        TextInput::make('stock')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->label('Stock Quantity'),
                    ])
                    ->columns(2),

                Section::make('Organization')
                    ->description('Category and brand information')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Category'),
                        
                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Brand'),
                        
                        Select::make('status')
                            ->options([
                                'published' => 'Published',
                                'draft' => 'Draft',
                                'archived' => 'Archived',
                            ])
                            ->default('draft')
                            ->required()
                            ->label('Status'),
                    ])
                    ->columns(3),

                Section::make('Media')
                    ->description('Product images')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory('products')
                            ->columnSpanFull()
                            ->label('Product Image'),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                InfoSection::make('Product Details')
                    ->schema([
                        TextEntry::make('name')->label('Product Name'),
                        TextEntry::make('price')->money('USD')->label('Price'),
                        TextEntry::make('stock')->label('Stock'),
                        TextEntry::make('status')->badge()->label('Status'),
                    ])
                    ->columns(2),
                
                InfoSection::make('Organization')
                    ->schema([
                        TextEntry::make('category.name')->label('Category'),
                        TextEntry::make('brand.name')->label('Brand'),
                    ])
                    ->columns(2),
                
                InfoSection::make('Details')
                    ->schema([
                        TextEntry::make('description')->label('Description'),
                        ImageEntry::make('image')->label('Product Image'),
                        TextEntry::make('created_at')->dateTime()->label('Created'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
