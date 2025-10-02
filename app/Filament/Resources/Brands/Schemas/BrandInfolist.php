<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Brand Details')
                ->schema([
                        ImageEntry::make('logo')->label('Logo'),
                        TextEntry::make('name')->label('Name')->extraAttributes(['class' => 'text-lg font-semibold']),
                        TextEntry::make('products_count')->label('Products')->extraAttributes(['class' => 'badge']),
                ])
                ->columns(2),
        ]);
    }
}
