<?php

namespace App\Filament\Admin\Resources\Transactions\Pages;

use App\Filament\Admin\Resources\Transactions\TransactionResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Transaction')
                    ->schema([
                        TextEntry::make('invoice')
                            ->label('Invoice'),

                        TextEntry::make('status')
                            ->label('Status'),

                        TextEntry::make('total')
                            ->label('Total')
                            ->money('IDR', locale: 'id'),

                        TextEntry::make('created_at')
                            ->label('Date')
                            ->dateTime(),
                    ]),

                Section::make('Customer')
                    ->schema([
                        TextEntry::make('customer.name')
                            ->label('Customer'),
                    ]),

                Section::make('Shipping')
                    ->schema([
                        TextEntry::make('province_name')
                            ->label('Province'),

                        TextEntry::make('city_name')
                            ->label('City'),

                        TextEntry::make('district_name')
                            ->label('District'),

                        TextEntry::make('full_address')
                            ->label('Address'),
                    ]),

                Section::make('Transaction Details')
                    ->schema([
                        TextEntry::make('transactionDetails')
                            ->label('Products')
                            ->getStateUsing(function ($record) {
                                return $record->transactionDetails
                                    ->count() . ' item(s)';
                            }),
                    ]),

            ]);
    }
}