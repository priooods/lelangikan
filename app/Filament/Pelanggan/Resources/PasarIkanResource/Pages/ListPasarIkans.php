<?php

namespace App\Filament\Pelanggan\Resources\PasarIkanResource\Pages;

use App\Filament\Pelanggan\Resources\PasarIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPasarIkans extends ListRecords
{
    protected static string $resource = PasarIkanResource::class;
    protected static ?string $title = 'Pasar Ikan';
    protected ?string $heading = 'Pasar Ikan';
    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
