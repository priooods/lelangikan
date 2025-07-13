<?php

namespace App\Filament\Resources\LelangIkanResource\Pages;

use App\Filament\Resources\LelangIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLelangIkan extends CreateRecord
{
    protected static string $resource = LelangIkanResource::class;
    protected ?string $heading = 'Tambah Data Lelang';
    protected static ?string $title = 'Tambah Lelang';
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['m_status_tabs_id'] = 1;
        return $data;
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan Data');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('create');
    }
}
