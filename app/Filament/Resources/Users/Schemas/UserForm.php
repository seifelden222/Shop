<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account')
                ->description('Basic account information and credentials')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Full name')
                        ->placeholder('e.g. John Doe'),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->label('Email')
                        ->placeholder('user@example.com')
                        ->helperText('This email is used for login'),

                    TextInput::make('password')
                        ->password()
                        ->nullable()
                        ->dehydrated(fn ($state) => filled($state))
                        ->label('Password')
                        ->helperText('Leave empty to keep the current password'),

                    Select::make('role')
                        ->options([
                            'super_admin' => 'Super Admin',
                            'admin' => 'Admin',
                            'user' => 'User',
                        ])
                        ->required()
                        ->default('user')
                        ->label('Role')
                        ->helperText('Assign a role to the user'),
                ])
                ->columns(2),

            Section::make('Preferences')
                ->schema([
                    // Placeholder for additional user settings; keep one-row layout by default
                    Grid::make(1)->schema([]),
                ]),
        ]);
    }
}
