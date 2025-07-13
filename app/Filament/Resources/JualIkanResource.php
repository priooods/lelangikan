<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JualIkanResource\Pages;
use App\Filament\Resources\JualIkanResource\RelationManagers;
use App\Models\JualIkan;
use App\Models\TIkanStockTab;
use App\Models\TIkanTabs;
use App\Models\TPenjualanTabs;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JualIkanResource extends Resource
{
    protected static ?string $model = TPenjualanTabs::class;
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Jual Ikan';
    protected static ?string $breadcrumb = "Jual Ikan";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Select::make('t_ikan_tabs_id')
                ->label('Nama Ikan')
                ->placeholder('Cari Nama Ikan')
                ->getSearchResultsUsing(
                    function (string $search) {
                        return TIkanTabs::where('type',0)->with('ikan', 'stock', 'penjualan', 'pelelangan')
                            ->whereDoesntHaveRelation('penjualan','id','!=', 't_ikan_tabs_id')
                            ->whereDoesntHaveRelation('pelelangan', 'id', '!=', 't_ikan_tabs_id')
                            ->whereHas('stock', function ($a) {
                                $a->where('stock','>',0);
                            })
                            ->whereHas('ikan', function ($a) use ($search) {
                                $a->where('fish_name', 'like', '%' . $search . '%');
                            })
                            ->limit(10)
                            ->get()
                            ->map(function (TIkanTabs $item) {
                                $offer = TIkanTabs::where('type', 0)->with('ikan', 'stock', 'nelayan')->find($item->id);
                                $item->name = $offer?->ikan?->fish_name . ' - ' . $offer?->weight . ' (Kg) - Nelayan : ' . $offer?->nelayan?->name;
                                return $item;
                            })
                            ->pluck('name', 'id');
                    }
                )
                ->searchable()
                ->required(),
            TextInput::make('price')->label('Harga Ikan')->numeric()->prefix('Rp.')->required()->placeholder('Masukan Deskripsi'),
            Textarea::make('description')->label('Deskripsi')->required()->placeholder('Masukan Deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            //     ->query(
            //     // TPenjualanTabs::where('')->with('transaction')->whereHas('transaction', function ($a) {
            //     //     $a->whereNotIn('m_status_tabs_id', [4, 6]);
            //     // })
            // )
            ->searchable()
            ->emptyStateHeading('Belum ada data product')
            ->columns([
            TextColumn::make('t_ikan_tabs_id')
                ->label('Nama Ikan')->searchable()
                ->description(fn(TPenjualanTabs $record): string => $record->description)
                ->getStateUsing(fn($record) => $record->ikan ? $record->ikan?->ikan?->fish_name : '-'),
            TextColumn::make('t_ikan_tabs_id.weight')->label('Berat (Kg)')
                ->getStateUsing(fn($record) => $record->ikan ? $record->ikan?->weight : '-')->alignment(Alignment::Center)
                ->formatStateUsing(fn(string $state): string => __("{$state} Kg")),
            TextColumn::make('price')->label('Harga')->formatStateUsing(fn(string $state): string => __("Rp. {$state}")),
            ImageColumn::make('m_ikan_tabs_id.path')->label('Foto')->alignment(Alignment::Center)->getStateUsing(function (TPenjualanTabs $record): string {
                return $record->ikan?->ikan?->fish_picture;
            }),
            TextColumn::make('m_status_tabs_id')->label('Status')->alignment(Alignment::Center)->badge()->color(fn(string $state): string => match ($state) {
                'Draft' => 'gray',
                'Terjual' => 'success',
                'Available' => 'success',
                'Not Available' => 'danger',
            })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Tidak Ada'),
            TextColumn::make('transaction')->label('Terjual')->alignment(Alignment::Center)->badge()->color(fn(string $state): string => match ($state) {
                'Terjual' => 'success',
                'Belum Terjual' => 'danger',
            })->getStateUsing(fn($record) => $record->transaction ? 'Terjual' : 'Belum Terjual'),
            ])
            ->filters([
                //
            ])
            ->actions([
            ActionGroup::make([
                Action::make('Available')
                    ->label('Available')
                    ->action(function ($record) {
                        $record->update([
                            'm_status_tabs_id' => 2,
                        ]);
                    })
                    ->visible(fn($record) => $record->m_status_tabs_id === 1 || $record->m_status_tabs_id === 3)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Publish Product Jualan')
                    ->modalDescription('Apakah anda ingin Publish Ikan ?')
                    ->modalSubmitActionLabel('Ikan Available')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('NotAvailable')
                    ->label('Not Available')
                    ->action(function ($record) {
                        $record->update([
                            'm_status_tabs_id' => 3,
                        ]);
                    })
                    ->visible(fn($record) => $record->m_status_tabs_id === 2)
                    ->icon('heroicon-o-check')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tarik Product Jualan')
                    ->modalDescription('Apakah Ikan Anda Not Available ?')
                    ->modalSubmitActionLabel('Ikan Not Available')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Tables\Actions\EditAction::make(),
            ])->button()->label('Aksi')->color('info')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJualIkans::route('/'),
            'create' => Pages\CreateJualIkan::route('/create'),
            'edit' => Pages\EditJualIkan::route('/{record}/edit'),
        ];
    }
}
