<?php 
 
namespace App\Filament\Admin\Resources\Categories\Tables; 
 
use Filament\Actions\BulkActionGroup; 
use Filament\Actions\DeleteBulkAction; 
use Filament\Actions\EditAction; 
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table; 
 
class CategoriesTable 
{ 
    public static function configure(Table $table): Table 
    { 
        return $table 
            ->columns([ 
                ImageColumn::make('image')
                    ->label('Category Image')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable(),
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