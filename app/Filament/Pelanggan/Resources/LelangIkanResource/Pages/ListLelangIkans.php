<?php

namespace App\Filament\Pelanggan\Resources\LelangIkanResource\Pages;

use App\Filament\Pelanggan\Resources\LelangIkanResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListLelangIkans extends ListRecords
{
    protected static string $resource = LelangIkanResource::class;
    protected static ?string $title = 'Lelang Ikan';
    protected ?string $heading = 'Lelang Ikan';
    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'join' => Tab::make('Lelang')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('m_status_tabs_id', 2)->with('mylelang')->doesntHave('mylelang')),
            'log' => Tab::make('History')
                ->modifyQueryUsing(fn(Builder $query) => $query->with('mylelang')->has('mylelang')),
        ];
    }
}
