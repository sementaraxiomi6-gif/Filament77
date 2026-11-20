<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([

                        FileUpload::make('image')
                            ->label('Product Image')
                            ->placeholder('Product Image')
                            ->required(),

                        TextInput::make('title')
                            ->label('Product Title')
                            ->placeholder('Product Title')
                            ->required(),

                        Grid::make(3)
                            ->schema([

                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Price')
                                    ->placeholder('Price')
                                    ->required(),

                                TextInput::make('weight')
                                    ->label('Weight')
                                    ->placeholder('Weight')
                                    ->required(),

                            ]),

                        RichEditor::make('description')
                            ->label('Product Description')
                            ->placeholder('Product Description')
                            ->required(),

                    ]),
            ]);
    }
}