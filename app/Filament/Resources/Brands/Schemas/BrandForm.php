<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Brand')
                ->schema([
                    TextInput::make('name')->required()->label('Brand Name')->placeholder('e.g. Nike'),
                    FileUpload::make('logo')->image()->label('Logo')->columnSpanFull()->helperText('Square images look best'),
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
