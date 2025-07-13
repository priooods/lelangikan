<?php

namespace App\Filament\Resources\MasterNelayanResource\Pages;

use App\Filament\Resources\MasterNelayanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterNelayan extends CreateRecord
{
    protected static string $resource = MasterNelayanResource::class;
    protected ?string $heading = 'Tambah Data Nelayan';
    protected static ?string $title = 'Tambah Nelayan';
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['m_status_tabs_id'] = 9;
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
