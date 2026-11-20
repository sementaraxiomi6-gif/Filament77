<?php 
 
namespace App\Filament\Admin\Resources\Sliders\Tables; 
 
use Filament\Actions\BulkActionGroup; 
use Filament\Actions\DeleteBulkAction; 
use Filament\Actions\EditAction; 
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table; 
 
class SlidersTable 
{ 
    public static function configure(Table $table): Table 
    { 
        return $table 
            ->columns([

                ImageColumn::make('image')
                    ->label('Slider Image'),

                TextColumn::make('link')
                    ->label('Link'),

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