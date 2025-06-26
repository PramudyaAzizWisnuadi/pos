<?php

namespace App\Filament\Resources\MasterbarangResource\Pages;

use App\Filament\Resources\MasterbarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterbarangs extends ListRecords
{
    protected static string $resource = MasterbarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
