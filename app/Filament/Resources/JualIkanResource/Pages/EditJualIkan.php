<?php

namespace App\Filament\Resources\JualIkanResource\Pages;

use App\Filament\Resources\JualIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJualIkan extends EditRecord
{
    protected static string $resource = JualIkanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
