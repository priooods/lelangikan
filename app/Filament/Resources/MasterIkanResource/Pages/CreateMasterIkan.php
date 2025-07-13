<?php

namespace App\Filament\Resources\MasterIkanResource\Pages;

use App\Filament\Resources\MasterIkanResource;
use App\Models\TIkanStockTab;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterIkan extends CreateRecord
{
    protected static string $resource = MasterIkanResource::class;
    protected ?string $heading = 'Tambah Data Ikan';
    protected static ?string $title = 'Tambah Ikan';
    protected static bool $canCreateAnother = false;

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan Data');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('create');
    }

    protected function afterCreate(): void
    {
        $find = TIkanStockTab::where('m_ikan_tabs_id', $this->record->m_ikan_tabs_id)->first();
        if(isset($find)){
            $find->update([
                'stock' => $find->stock + $this->record->count
            ]);
        } else {
            TIkanStockTab::create([
                'm_ikan_tabs_id' => $this->record->m_ikan_tabs_id,
                'stock' => $this->record->count,
            ]);
        }
    }
}
