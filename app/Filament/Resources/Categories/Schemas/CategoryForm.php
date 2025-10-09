<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
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
                    Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->default('active')
                        ->required()
                        ->label('Status'),
                ])
                ->columns(2),
        ]);
    }
}
