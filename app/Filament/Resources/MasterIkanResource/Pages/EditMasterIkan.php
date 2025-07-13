<?php

namespace App\Filament\Resources\MasterIkanResource\Pages;

use App\Filament\Resources\MasterIkanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterIkan extends EditRecord
{
    protected static string $resource = MasterIkanResource::class;
    protected static ?string $title = 'Edit Ikan';
    protected ?string $heading = 'Edit Ikan';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
