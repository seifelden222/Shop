<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid as InfoGrid;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
        Grid::make(2)
                ->schema([
                    Section::make('Profile')
                        ->schema([
                            ImageEntry::make('avatar')->label('Avatar'),
                            TextEntry::make('name')->label('Name')->extraAttributes(['class' => 'text-lg font-semibold']),
                            TextEntry::make('email')->label('Email'),
                            TextEntry::make('role')->label('Role'),
                        ])
                        ->columns(1),

                    Section::make('Details')
                        ->schema([
                            TextEntry::make('cart_count')->label('Cart Items'),
                            TextEntry::make('orders_count')->label('Orders'),
                            TextEntry::make('created_at')->dateTime()->label('Created'),
                        ])
                        ->columns(1),
                ]),
        ]);
    }
}
