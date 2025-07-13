<?php

namespace App\Filament\Resources\MasterPelangganResource\Pages;

use App\Filament\Resources\MasterPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterPelanggans extends ListRecords
{
    protected static string $resource = MasterPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
