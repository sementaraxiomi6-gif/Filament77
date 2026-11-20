<?php

namespace App\Filament\Admin\Resources\Ratings\Pages;

use App\Filament\Admin\Resources\Ratings\RatingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRating extends EditRecord
{
    protected static string $resource = RatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
