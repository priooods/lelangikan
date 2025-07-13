<?php

namespace App\Filament\Resources\MasterPelangganResource\Pages;

use App\Filament\Resources\MasterPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterPelanggan extends EditRecord
{
    protected static string $resource = MasterPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
