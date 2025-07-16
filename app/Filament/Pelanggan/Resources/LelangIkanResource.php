<?php

namespace App\Filament\Pelanggan\Resources;

use App\Filament\Pelanggan\Resources\LelangIkanResource\Pages;
use App\Filament\Pelanggan\Resources\LelangIkanResource\RelationManagers;
use App\Models\LelangIkan;
use App\Models\TLelangDetailTabs;
use App\Models\TLelangTabs;
use App\Models\TPenjualanTransactionTabs;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LelangIkanResource extends Resource
{
    protected static ?string $model = TLelangTabs::class;
    protected static ?string $navigationLabel = 'Lelang Ikan';
    protected static ?string $breadcrumb = "Lelang";
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
            ->columns([
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
            TextColumn::make('m_status_tabs_id')->label('Status Lelang')->alignment(Alignment::Center)->badge()->color(fn(string $state): string => match ($state) {
                'Draft' => 'gray',
                'Available' => 'success',
                'Not Available' => 'danger',
            })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Tidak Ada'),
            TextColumn::make('mylelang.m_status_tabs_id')->label('Status')->alignment(Alignment::Center)->badge()->color(fn(string $state): string => match ($state) {
                'Pengajuan' => 'info',
                'Disetujui' => 'success',
                'Ditolak' => 'danger',
                'Belum Join' => 'danger',
            })->getStateUsing(fn($record) => $record->mylelang ? $record->mylelang->status->title : 'Belum Join')
            ])
            ->filters([
                //
            ])
            ->actions([
            Action::make('join')
                ->label('Ikut Lelang')
                ->action(function (array $data, $record) {
                    TLelangDetailTabs::create([
                        'users_id' => auth()->user()->id,
                        't_lelang_tabs_id' => $record->id,
                        'm_status_tabs_id' => 11,
                        'description' => $data['description'],
                    ]);
                })
                ->form([
                    Textarea::make('description')->label('Catatan (Optional)')->placeholder('Masukan Catatan'),
                ])
                ->visible(fn($record) => $record->m_status_tabs_id === 2 && $record->mylelang === null)
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Ikut Lelang Sekarang')
                ->modalDescription('Apakah anda yakin ingin mengikuti lelang ?')
                ->modalSubmitActionLabel('Ikut Sekarang')
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
            'index' => Pages\ListLelangIkans::route('/'),
            'create' => Pages\CreateLelangIkan::route('/create'),
            'edit' => Pages\EditLelangIkan::route('/{record}/edit'),
        ];
    }
}
