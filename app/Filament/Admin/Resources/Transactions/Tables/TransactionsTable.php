<?php

namespace App\Filament\Admin\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('cart.product.title')
                    ->label('Product')
                    ->searchable(),

                TextColumn::make('cart.customer.name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('cart.qty')
                    ->label('Qty'),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}