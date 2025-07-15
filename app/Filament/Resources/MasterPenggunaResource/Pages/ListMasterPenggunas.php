<?php

namespace App\Filament\Resources\MasterPenggunaResource\Pages;

use App\Filament\Resources\MasterPenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterPenggunas extends ListRecords
{
    protected static string $resource = MasterPenggunaResource::class;

    protected static ?string $title = 'Pengguna';
    protected ?string $heading = 'Data Pengguna';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pengguna')
                ->visible(auth()->user()->m_user_role_tabs_id == 1 || auth()->user()->m_user_role_tabs_id == 2),
        ];
    }
}
