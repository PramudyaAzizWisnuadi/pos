<?php

namespace App\Filament\Resources\MasterbarangResource\Pages;

use App\Filament\Resources\MasterbarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterbarang extends CreateRecord
{
    protected static string $resource = MasterbarangResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
