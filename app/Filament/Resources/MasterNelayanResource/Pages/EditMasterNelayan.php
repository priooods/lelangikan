<?php

namespace App\Filament\Resources\MasterNelayanResource\Pages;

use App\Filament\Resources\MasterNelayanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterNelayan extends EditRecord
{
    protected static string $resource = MasterNelayanResource::class;
    protected static ?string $title = 'Edit Nelayan';
    protected ?string $heading = 'Edit Nelayan';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
