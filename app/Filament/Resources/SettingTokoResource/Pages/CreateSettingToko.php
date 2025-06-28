<?php

namespace App\Filament\Resources\SettingTokoResource\Pages;

use App\Filament\Resources\SettingTokoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSettingToko extends CreateRecord
{
    protected static string $resource = SettingTokoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pastikan hanya ada 1 setting toko
        if (\App\Models\SettingToko::count() > 0) {
            $this->halt();
            $this->notify('danger', 'Setting toko sudah ada!');
        }

        return $data;
    }
}
