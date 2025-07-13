<?php

namespace App\Filament\Resources\MasterNelayanResource\Pages;

use App\Filament\Resources\MasterNelayanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterNelayans extends ListRecords
{
    protected static string $resource = MasterNelayanResource::class;
    protected static ?string $title = 'Nelayan';
    protected ?string $heading = 'Data Nelayan';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Nelayan'),
        ];
    }
}
