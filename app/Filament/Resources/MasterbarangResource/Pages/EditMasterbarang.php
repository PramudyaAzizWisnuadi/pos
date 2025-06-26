<?php

namespace App\Filament\Resources\MasterbarangResource\Pages;

use App\Filament\Resources\MasterbarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterbarang extends EditRecord
{
    protected static string $resource = MasterbarangResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
