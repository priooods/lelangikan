<?php

namespace App\Filament\Pelanggan\Resources\LelangIkanResource\Pages;

use App\Filament\Pelanggan\Resources\LelangIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLelangIkans extends ListRecords
{
    protected static string $resource = LelangIkanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
