<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LelangIkanResource\Pages;
use App\Filament\Resources\LelangIkanResource\RelationManagers;
use App\Models\LelangIkan;
use App\Models\TIkanTabs;
use App\Models\TLelangDetailTabs;
use App\Models\TLelangTabs;
use App\Models\TPenjualanTabs;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class LelangIkanResource extends Resource
{
    protected static ?string $model = TLelangTabs::class;
    protected static ?string $navigationGroup = 'Pelelangan';
    protected static ?string $navigationLabel = 'Lelang Ikan';
    protected static ?string $breadcrumb = "Lelang Ikan";
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
                        return TIkanTabs::where('type', 0)->with('ikan', 'stock', 'penjualan', 'pelelangan')
                        ->whereDoesntHaveRelation('penjualan', 'id', '!=', 't_ikan_tabs_id')
                        ->whereDoesntHaveRelation('pelelangan', 'id', '!=', 't_ikan_tabs_id')
                        ->whereHas('stock', function ($a) {
                            $a->where('stock', '>', 0);
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
            DatePicker::make('start_date_lelang')->label('Mulai Lelang')->required(),
            DatePicker::make('end_date_lelang')->label('Selesai Lelang')->required(),
            Textarea::make('description')->label('Judul Lelang')->placeholder('Masukan Judul Lelang'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchable()
            ->emptyStateHeading('Belum ada data product')
            ->columns([
            TextColumn::make('description')->label('Judul'),
            TextColumn::make('t_ikan_tabs_id')
                ->label('Nama Ikan')->searchable()
                ->description(fn(TLelangTabs $record): string => $record->description ?? '-')
                ->getStateUsing(fn($record) => $record->ikan ? $record->ikan?->ikan?->fish_name : '-'),
            TextColumn::make('start_date_lelang')->label('Mulai')->date()->alignment(Alignment::Center),
            TextColumn::make('end_date_lelang')->label('Selesai')->date()->alignment(Alignment::Center),
            TextColumn::make('t_ikan_tabs_id.weight')->label('Berat (Kg)')
                ->getStateUsing(fn($record) => $record->ikan ? $record->ikan?->weight : '-')->alignment(Alignment::Center)
                ->formatStateUsing(fn(string $state): string => __("{$state} Kg")),
            ImageColumn::make('m_ikan_tabs_id.path')->label('Foto')->alignment(Alignment::Center)->getStateUsing(function (TLelangTabs $record): string {
                return $record->ikan?->ikan?->fish_picture;
            }),
            TextColumn::make('m_status_tabs_id')->label('Status')->alignment(Alignment::Center)->badge()->color(fn(string $state): string => match ($state) {
                'Draft' => 'gray',
                'Available' => 'success',
                'Not Available' => 'danger',
            })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Tidak Ada'),
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
                    ->modalHeading('Publish Lelang Anda')
                    ->modalDescription('Apakah anda ingin Publish Lelang ?')
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
                    ->modalHeading('Tarik Lelang Anda')
                    ->modalDescription('Apakah Lelang Anda selesai ?')
                    ->modalSubmitActionLabel('Lelang selesai')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('Winner')
                    ->label('Atur Pemenang')
                    ->form([
                        Select::make('select_pemenang')
                            ->label('Pilih Pemenang Lelang')
                            ->placeholder('Cari nama peserta')
                            ->required()
                            ->searchable()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->getSearchResultsUsing(
                                function ($record, string $search) {
                                    return TLelangDetailTabs::where('t_lelang_tabs_id', $record->id)->where('m_status_tabs_id', 12)->with('user')
                                        ->whereHas('user', function ($a) use ($search) {
                                            $a->where('name', 'like', '%' . $search . '%');
                                        })
                                        ->limit(10)
                                        ->get()
                                        ->map(function (TLelangDetailTabs $item) {
                                            $offer = TLelangDetailTabs::with('user')->find($item->id);
                                            $item->name = $offer?->user?->name;
                                            return $item;
                                        })
                                        ->pluck('name', 'id');
                                }
                            )
                    ])
                    ->action(function ($record) {
                        TLelangDetailTabs::where('t_lelang_tabs_id', $record->id)->update([
                            'pemenang' => 1
                        ]);
                    })
                    ->visible(fn($record) => $record->findpemenang == null)
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Atur Pemenang Lelang')
                    ->modalDescription('Apakah lelang telah selesai ?')
                    ->modalSubmitActionLabel('Simpan Pemenang')
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
            'index' => Pages\ListLelangIkans::route('/'),
            'create' => Pages\CreateLelangIkan::route('/create'),
            'edit' => Pages\EditLelangIkan::route('/{record}/edit'),
        ];
    }
}
