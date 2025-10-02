<?php

namespace App\Filament\Resources\Carts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;

class CartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsSection::make('Cart Item')
                ->schema([
                    Select::make('product_id')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Product'),

                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('User'),

                    TextInput::make('quantity')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->label('Quantity')
                        ->helperText('Minimum 1'),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'active' => 'Active',
                            'saved' => 'Saved',
                            'removed' => 'Removed',
                        ])
                        ->default('pending')
                        ->required()
                        ->label('Status')
                        ->helperText('Choose the cart item status'),
                ])
                ->columns(2),
        ]);
    }
}
