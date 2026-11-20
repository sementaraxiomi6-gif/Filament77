<?php

namespace App\Filament\Admin\Resources\Ratings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RatingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('product.title')
                    ->label('Product')
                    ->searchable(),

                TextColumn::make('rating')
                    ->label('Rating'),

                TextColumn::make('review')
                    ->label('Review')
                    ->limit(50),

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