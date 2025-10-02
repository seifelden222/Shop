<?php

namespace App\Filament\Resources\Carts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CartInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Cart Details')
                ->schema([
                    ImageEntry::make('product.main_image')->label('Product Image'),
                    TextEntry::make('product.name')->label('Product'),
                    TextEntry::make('product.price')->money('USD')->label('Unit Price'),
                    TextEntry::make('quantity')->label('Quantity'),
                    TextEntry::make('user.name')->label('User'),
                ])
                ->columns(2),
        ]);
    }
}
