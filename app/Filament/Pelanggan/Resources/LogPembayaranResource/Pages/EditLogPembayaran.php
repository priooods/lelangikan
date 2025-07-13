<?php

namespace App\Filament\Pelanggan\Resources\LogPembayaranResource\Pages;

use App\Filament\Pelanggan\Resources\LogPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogPembayaran extends EditRecord
{
    protected static string $resource = LogPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
