<?php

namespace App\Filament\Resources\SettingTokoResource\Pages;

use App\Filament\Resources\SettingTokoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\SettingToko;

class ListSettingTokos extends ListRecords
{
    protected static string $resource = SettingTokoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn() => SettingToko::count() === 0),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // Jika sudah ada setting dan hanya 1, redirect ke edit
        $setting = SettingToko::first();
        if ($setting && SettingToko::count() === 1) {
            redirect()->route('filament.admin.resources.setting-tokos.edit', $setting);
        }
    }
}
