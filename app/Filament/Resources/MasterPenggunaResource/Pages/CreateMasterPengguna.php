<?php

namespace App\Filament\Resources\MasterPenggunaResource\Pages;

use App\Filament\Resources\MasterPenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterPengguna extends CreateRecord
{
    protected static string $resource = MasterPenggunaResource::class;
    protected ?string $heading = 'Tambah Data Pengguna';
    protected static ?string $title = 'Tambah Pengguna';
    protected static bool $canCreateAnother = false;
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
