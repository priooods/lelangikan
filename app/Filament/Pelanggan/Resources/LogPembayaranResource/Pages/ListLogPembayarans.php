<?php

namespace App\Filament\Pelanggan\Resources\LogPembayaranResource\Pages;

use App\Filament\Pelanggan\Resources\LogPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogPembayarans extends ListRecords
{
    protected static string $resource = LogPembayaranResource::class;
    protected static ?string $title = 'Riwayat Transaction';
    protected ?string $heading = 'Data Riwayat Transaction';
    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
