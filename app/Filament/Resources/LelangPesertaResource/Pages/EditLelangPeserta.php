<?php

namespace App\Filament\Resources\LelangPesertaResource\Pages;

use App\Filament\Resources\LelangPesertaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLelangPeserta extends EditRecord
{
    protected static string $resource = LelangPesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
