<?php

namespace App\Filament\Resources\MasterPenggunaResource\Pages;

use App\Filament\Resources\MasterPenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterPengguna extends EditRecord
{
    protected static string $resource = MasterPenggunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
