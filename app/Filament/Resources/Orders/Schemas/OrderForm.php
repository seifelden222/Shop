<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Information')
                ->schema([
                    TextInput::make('order_number')->label('Order #'),
                    TextInput::make('user_id')->label('User ID'),
                    TextInput::make('status')->label('Status'),
                    Textarea::make('notes')->label('Notes')->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
