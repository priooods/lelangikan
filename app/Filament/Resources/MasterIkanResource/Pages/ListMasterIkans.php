<?php

namespace App\Filament\Resources\MasterIkanResource\Pages;

use App\Filament\Resources\MasterIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterIkans extends ListRecords
{
    protected static string $resource = MasterIkanResource::class;
    protected static ?string $title = 'Ikan';
    protected ?string $heading = 'Data Ikan';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Ikan')
                ->visible(auth()->user()->m_user_role_tabs_id == 1 || auth()->user()->m_user_role_tabs_id == 2),
        ];
    }
}
