<?php

namespace App\Filament\Pelanggan\Resources;

use App\Filament\Pelanggan\Resources\LogPembayaranResource\Pages;
use App\Filament\Pelanggan\Resources\LogPembayaranResource\RelationManagers;
use App\Models\LogPembayaran;
use App\Models\TPenjualanTransactionTabs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogPembayaranResource extends Resource
{
    protected static ?string $model = TPenjualanTransactionTabs::class;
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Riwayat Pembelian';
    protected static ?string $breadcrumb = "Riwayat";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Product')
                ->description('Informasi detail ikan yang dijual')
                ->schema([
                    TextEntry::make('penjualan.ikan')
                        ->label('Nama Ikan')
                        ->getStateUsing(fn($record) => $record->penjualan ? $record->penjualan?->ikan?->ikan?->fish_name : '-'),
                    TextEntry::make('t_ikan_tabs_id.weight')->label('Berat (Kg)')
                        ->getStateUsing(fn($record) => $record->penjualan ? $record->penjualan?->ikan?->weight : '-')
                        ->formatStateUsing(fn(string $state): string => __("{$state} Kg")),
                    TextEntry::make('price')->label('Harga')
                        ->getStateUsing(fn($record) => $record->penjualan ? $record->penjualan?->price : '-')
                        ->formatStateUsing(fn(string $state): string => __("Rp. {$state}")),
                    ImageEntry::make('penjualan.ikan.path')->label('Foto')->getStateUsing(function (TPenjualanTransactionTabs $record): string {
                        return $record->penjualan?->ikan?->ikan?->fish_picture;
                    }),
                ])->columns(2),
            Section::make('Bukti Bayar')
                ->description('Informasi bukti bayar')
                ->schema([
                    TextEntry::make('amount_paid')
                        ->label('Dibayarkan')->formatStateUsing(fn(string $state): string => __("Rp. {$state}")),
                    TextEntry::make('amount_change')
                        ->label('Kembalian')
                        ->formatStateUsing(fn(string $state): string => __("Rp. {$state}")),
                    TextEntry::make('m_status_tabs_id')->label('Status')->badge()->color(fn(string $state): string => match ($state) {
                        'Sending Payment' => 'info',
                        'Paid Payment' => 'success',
                        'Refund Payment' => 'danger',
                    })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Tidak Ada'),
                    ImageEntry::make('payment_path')->label('Bukti Bayar')->getStateUsing(function (TPenjualanTransactionTabs $record): string {
                        return $record->payment_path;
                    }),
                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                TPenjualanTransactionTabs::where('users_id', auth()->user()->id)
            )
            ->searchable()
            ->emptyStateHeading('Belum ada data history')
            ->columns([
            TextColumn::make('penjualan.ikan')
                ->label('Nama Ikan')->searchable()
                ->description(fn(TPenjualanTransactionTabs $record): string => $record->penjualan?->description ?? '')
                ->getStateUsing(fn($record) => $record->penjualan ? $record->penjualan?->ikan?->ikan?->fish_name : '-'),
            TextColumn::make('t_ikan_tabs_id.weight')->label('Berat (Kg)')
                ->getStateUsing(fn($record) => $record->penjualan ? $record->penjualan?->ikan?->weight : '-')->alignment(Alignment::Center)
                ->formatStateUsing(fn(string $state): string => __("{$state} Kg")),
            TextColumn::make('price')->label('Harga')
                ->getStateUsing(fn($record) => $record->penjualan ? $record->penjualan?->price : '-')
                ->formatStateUsing(fn(string $state): string => __("Rp. {$state}")),
            ImageColumn::make('penjualan.ikan.path')->label('Foto')->alignment(Alignment::Center)->getStateUsing(function (TPenjualanTransactionTabs $record): string {
                return $record->penjualan?->ikan?->ikan?->fish_picture;
            }),
            TextColumn::make('m_status_tabs_id')->label('Status')->alignment(Alignment::Center)->badge()->color(fn(string $state): string => match ($state) {
                'Sending Payment' => 'info',
                'Paid Payment' => 'success',
                'Refund Payment' => 'danger',
            })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Tidak Ada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListLogPembayarans::route('/'),
            'create' => Pages\CreateLogPembayaran::route('/create'),
            'edit' => Pages\EditLogPembayaran::route('/{record}/edit'),
        ];
    }
}
