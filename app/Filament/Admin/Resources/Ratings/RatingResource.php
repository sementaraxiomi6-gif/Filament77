<?php

namespace App\Filament\Admin\Resources\Ratings;

use App\Filament\Admin\Resources\Ratings\Pages\ListRatings;
use App\Filament\Admin\Resources\Ratings\Schemas\RatingForm;
use App\Filament\Admin\Resources\Ratings\Tables\RatingsTable;
use App\Models\Rating;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RatingResource extends Resource
{
    protected static ?string $model = Rating::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|\UnitEnum|null $navigationGroup = 'Ratings & Customers';

    protected static ?string $pluralModelLabel = 'Ratings & Reviews';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return RatingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RatingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRatings::route('/'),
        ];
    }
}