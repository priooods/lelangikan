<?php

namespace App\Filament\Resources\JualIkanResource\Pages;

use App\Filament\Resources\JualIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJualIkans extends ListRecords
{
    protected static string $resource = JualIkanResource::class;
    protected static ?string $title = 'Penjualan Ikan';
    protected ?string $heading = 'Data Penjualan Ikan';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Product')
                ->visible(auth()->user()->m_user_role_tabs_id == 1 || auth()->user()->m_user_role_tabs_id == 2),
        ];
    }
}
