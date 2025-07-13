<?php

namespace App\Filament\Resources\LelangIkanResource\Pages;

use App\Filament\Resources\LelangIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLelangIkans extends ListRecords
{
    protected static string $resource = LelangIkanResource::class;
    protected static ?string $title = 'Lelang Ikan';
    protected ?string $heading = 'Data Lelang Ikan';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Product')
                ->visible(auth()->user()->m_user_role_tabs_id == 1 || auth()->user()->m_user_role_tabs_id == 2),
        ];
    }
}
