<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Details')
                ->schema([
                    TextEntry::make('order_number')->label('Order #'),
                    TextEntry::make('user.name')->label('Customer'),
                    TextEntry::make('status')->label('Status'),
                    TextEntry::make('total')->money('USD')->label('Total'),
                ])
                ->columns(2),
        ]);
    }
}
