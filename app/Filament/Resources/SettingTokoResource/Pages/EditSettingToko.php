<?php

namespace App\Filament\Resources\SettingTokoResource\Pages;

use App\Filament\Resources\SettingTokoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSettingToko extends EditRecord
{
    protected static string $resource = SettingTokoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada action delete
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
