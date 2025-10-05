<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->description('Basic order details')
                    ->schema([
                        TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Order Number'),
                        
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Customer (User)'),
                        
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required()
                            ->label('Order Status'),
                        
                        Placeholder::make('created_at')
                            ->label('Order Date')
                            ->content(fn ($record): string => $record?->created_at?->format('M d, Y H:i') ?? '-'),
                    ])
                    ->columns(2),

                Section::make('Customer Information')
                    ->description('Customer contact details')
                    ->schema([
                        TextInput::make('customer_name')
                            ->required()
                            ->label('Customer Name'),
                        
                        TextInput::make('customer_email')
                            ->email()
                            ->required()
                            ->label('Email'),
                        
                        TextInput::make('customer_phone')
                            ->tel()
                            ->label('Phone'),
                        
                        TextInput::make('city')
                            ->label('City'),
                        
                        TextInput::make('postal_code')
                            ->label('Postal Code'),
                        
                        Textarea::make('address')
                            ->required()
                            ->columnSpanFull()
                            ->label('Address'),
                    ])
                    ->columns(3),

                Section::make('Payment & Pricing')
                    ->description('Payment and financial details')
                    ->schema([
                        TextInput::make('total_price')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->label('Total Price'),
                        
                        TextInput::make('shipping_cost')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->label('Shipping Cost'),
                        
                        TextInput::make('currency')
                            ->default('USD')
                            ->label('Currency'),
                        
                        Select::make('payment_method')
                            ->options([
                                'credit_card' => 'Credit Card',
                                'paypal' => 'PayPal',
                                'stripe' => 'Stripe',
                                'cash_on_delivery' => 'Cash on Delivery',
                                'bank_transfer' => 'Bank Transfer',
                            ])
                            ->label('Payment Method'),
                        
                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->label('Payment Status'),
                        
                        TextInput::make('transaction_id')
                            ->label('Transaction ID'),
                        
                        TextInput::make('provider_order_id')
                            ->label('Provider Order ID'),
                    ])
                    ->columns(3),

                Section::make('Additional Information')
                    ->description('Notes and additional details')
                    ->schema([
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->label('Description'),
                        
                        Textarea::make('notes')
                            ->columnSpanFull()
                            ->label('Notes'),
                    ]),
            ]);
    }
}
