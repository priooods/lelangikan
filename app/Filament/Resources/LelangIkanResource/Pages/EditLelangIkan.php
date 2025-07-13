<?php

namespace App\Filament\Resources\LelangIkanResource\Pages;

use App\Filament\Resources\LelangIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLelangIkan extends EditRecord
{
    protected static string $resource = LelangIkanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
