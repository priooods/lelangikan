<?php

namespace App\Filament\Resources\TransaksiPenjualanResource\Pages;

use App\Filament\Resources\TransaksiPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiPenjualans extends ListRecords
{
    protected static string $resource = TransaksiPenjualanResource::class;
    protected static ?string $title = 'Payment';
    protected ?string $heading = 'Data Payment';
    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
