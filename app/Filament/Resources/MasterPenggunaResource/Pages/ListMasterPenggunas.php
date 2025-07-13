<?php

namespace App\Filament\Resources\MasterPenggunaResource\Pages;

use App\Filament\Resources\MasterPenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterPenggunas extends ListRecords
{
    protected static string $resource = MasterPenggunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
