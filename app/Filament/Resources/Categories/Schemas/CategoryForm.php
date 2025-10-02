<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Category')
                ->schema([
                    TextInput::make('name')->required()->label('Name'),
                    Textarea::make('description')->columnSpanFull()->label('Description'),
                ])
                ->columns(2),
        ]);
    }
}
