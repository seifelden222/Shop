<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Information')
                ->schema([
                    TextInput::make('order_number')
                        ->label('Order #')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Automatically generated on the server; not editable'),
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('User'),
                    Select::make('status')
                        ->options([
                            'processing' => 'Processing',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                            'refunded' => 'Refunded',
                        ])
                        ->default('processing')
                        ->required()
                        ->label('Status'),
                    Textarea::make('address')
                        ->label('Shipping Address')
                        ->required()
                        ->columnSpanFull()
                        ->placeholder('Full shipping address'),
                    Textarea::make('notes')->label('Notes')->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
