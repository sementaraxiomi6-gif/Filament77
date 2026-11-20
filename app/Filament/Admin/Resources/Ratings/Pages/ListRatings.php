<?php

namespace App\Filament\Admin\Resources\Ratings\Pages;

use App\Filament\Admin\Resources\Ratings\RatingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRatings extends ListRecords
{
    protected static string $resource = RatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
