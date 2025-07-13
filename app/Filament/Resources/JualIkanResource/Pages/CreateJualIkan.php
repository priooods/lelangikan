<?php

namespace App\Filament\Resources\JualIkanResource\Pages;

use App\Filament\Resources\JualIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJualIkan extends CreateRecord
{
    protected static string $resource = JualIkanResource::class;
    protected ?string $heading = 'Tambah Data Product';
    protected static ?string $title = 'Tambah Product';
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
