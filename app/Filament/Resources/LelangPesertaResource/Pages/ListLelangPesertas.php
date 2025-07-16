<?php

namespace App\Filament\Resources\LelangPesertaResource\Pages;

use App\Filament\Resources\LelangPesertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLelangPesertas extends ListRecords
{
    protected static string $resource = LelangPesertaResource::class;
    protected static ?string $title = 'Peserta Lelang';
    protected ?string $heading = 'Data Peserta Lelang';
    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
