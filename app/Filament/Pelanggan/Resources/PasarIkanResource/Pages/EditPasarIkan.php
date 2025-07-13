<?php

namespace App\Filament\Pelanggan\Resources\PasarIkanResource\Pages;

use App\Filament\Pelanggan\Resources\PasarIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPasarIkan extends EditRecord
{
    protected static string $resource = PasarIkanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
