<?php 
 
namespace App\Filament\Admin\Resources\Sliders\Schemas; 

use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema; 
 
class SliderForm
{ 
    public static function configure(Schema $schema): Schema 
    { 
        return $schema 
            ->components([

                Card::make()
                    ->schema([

                        FileUpload::make('image')
                            ->label('Slider Image')
                            ->placeholder('Slider Image')
                            ->required(),

                        TextInput::make('link')
                            ->label('Link')
                            ->placeholder('Link')
                            ->required(),

                    ]),

            ]); 
    } 
}