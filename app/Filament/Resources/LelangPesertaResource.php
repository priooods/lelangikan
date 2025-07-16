<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LelangPesertaResource\Pages;
use App\Filament\Resources\LelangPesertaResource\RelationManagers;
use App\Models\LelangPeserta;
use App\Models\TLelangDetailTabs;
use App\Models\TLelangTabs;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LelangPesertaResource extends Resource
{
    protected static ?string $model = TLelangDetailTabs::class;
    protected static ?string $navigationGroup = 'Pelelangan';
    protected static ?string $navigationLabel = 'Peserta';
    protected static ?string $breadcrumb = "Peserta";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('t_lelang_tabs_id')
                    ->label('Judul Lelang')->getTitleFromRecordUsing(fn(TLelangDetailTabs $record): string => $record->lelang->description),
            ])
            ->columns([
            TextColumn::make('t_lelang_tabs_id')
                ->label('Lelang')->searchable()
                ->getStateUsing(fn($record) => $record->lelang ? $record->lelang->description : '-'),
            TextColumn::make('users_id')
                ->label('Nama Peserta')->searchable()
                ->getStateUsing(fn($record) => $record->user ? $record->user?->name : '-'),
            TextColumn::make('m_status_tabs_id')->label('Status')->alignment(Alignment::Center)->badge()->color(fn(string $state): string => match ($state) {
                'Pengajuan' => 'info',
                'Disetujui' => 'success',
                'Ditolak' => 'danger',
                'Belum Join' => 'danger',
            })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Belum Join')
            ])
            ->filters([
                //
            ])
            ->actions([
            Action::make('join')
                ->label('Setujui')
                ->action(function (array $data, $record) {
                    TLelangDetailTabs::where('id', $record->id)->update([
                        'm_status_tabs_id' => 12,
                    ]);
                })
                ->visible(fn($record) => $record->m_status_tabs_id === 11)
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Setujui Peserta Lelang')
                ->modalDescription('Apakah anda yakin ingin menyetujui Peserta Lelang ?')
                ->modalSubmitActionLabel('Setujui')
                ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
            Action::make('diclaine')
                ->label('Tolak')
                ->action(function (array $data, $record) {
                    TLelangDetailTabs::where('id', $record->id)->update([
                        'm_status_tabs_id' => 13,
                    ]);
                })
                ->visible(fn($record) => $record->m_status_tabs_id === 11)
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Tolak Peserta Lelang')
                ->modalDescription('Apakah anda yakin ingin menolak Peserta Lelang ?')
                ->modalSubmitActionLabel('Setujui')
                ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
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
            'index' => Pages\ListLelangPesertas::route('/'),
            'create' => Pages\CreateLelangPeserta::route('/create'),
            'edit' => Pages\EditLelangPeserta::route('/{record}/edit'),
        ];
    }
}
