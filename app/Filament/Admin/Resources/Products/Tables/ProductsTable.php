<?php 
 
namespace App\Filament\Admin\Resources\Products\Tables; 
 
use Filament\Actions\BulkActionGroup; 
use Filament\Actions\DeleteBulkAction; 
use Filament\Actions\EditAction; 
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table; 
 
class ProductsTable
{ 
    public static function configure(Table $table): Table 
    { 
        return $table 
            ->columns([ 
                ImageColumn::make('image')
                    ->circular(),

                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('category.name')
                    ->searchable(),

                TextColumn::make('price')
                    ->numeric()
                    ->money('IDR', locale: 'id'),

                TextColumn::make('description')
                    ->limit(50),
            ]) 
            ->filters([ 
                // 
            ]) 
            ->recordActions([ 
                EditAction::make(), 
            ]) 
            ->toolbarActions([ 
                BulkActionGroup::make([ 
                    DeleteBulkAction::make(), 
                ]), 
            ]); 
    } 
}