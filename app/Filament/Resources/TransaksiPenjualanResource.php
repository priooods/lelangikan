<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiPenjualanResource\Pages;
use App\Filament\Resources\TransaksiPenjualanResource\RelationManagers;
use App\Models\TPenjualanTabs;
use App\Models\TPenjualanTransactionTabs;
use App\Models\TransaksiPenjualan;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiPenjualanResource extends Resource
{
    protected static ?string $model = TPenjualanTransactionTabs::class;
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Payment';
    protected static ?string $breadcrumb = "Payment";
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
                    ImageEntry::make('payment_path')->label('Bukti Bayar')->getStateUsing(function (TPenjualanTransactionTabs $record): string {
                        return $record->payment_path;
                    }),
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
                ])->columns(2),
            Section::make('Pelanggan')
                ->description('Informasi detail pelanggan')
                ->schema([
                    TextEntry::make('user.name')
                        ->label('Nama Pelanggan')
                        ->getStateUsing(fn($record) => $record->user ? $record->user?->name : '-'),
                    TextEntry::make('user.name')
                        ->label('Email Pelanggan')
                        ->getStateUsing(fn($record) => $record->user ? $record->user?->email : '-'),
                    TextEntry::make('user.name')
                        ->label('Gender')
                        ->getStateUsing(fn($record) => $record->gender == 0 ? 'Wanita' : 'Pria'),
                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchable()
            ->emptyStateHeading('Belum ada data payment')
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
                Tables\Actions\ViewAction::make()->modalHeading('Informasi Product'),
                Action::make('payment')
                    ->visible(fn($record) => $record->m_status_tabs_id === 4)
                    ->label('Payment')
                    ->action(function (array $data, $record) {
                        $record->update([
                            'amount_paid' => $data['amount_paid'],
                            'amount_change' => $data['amount_change'],
                            'm_status_tabs_id' => 6,
                        ]);
                        TPenjualanTabs::where('id',$record->t_penjualan_tabs_id)->update([
                            'm_status_tabs_id' => 10,
                        ]);
                    })
                    ->form([
                        TextInput::make('amount_paid')->label('Jumlah diterima')->numeric()->placeholder('Masukan jumlah uang diterima'),
                        TextInput::make('amount_change')->default(0)->label('Kembalian')->required()->numeric()->placeholder('Masukan Kembalian'),
                    ])
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Apakah anda telah menerima pembayaran ?')
                    ->modalDescription('')
                    ->modalSubmitActionLabel('Terima Payment')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
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
            'index' => Pages\ListTransaksiPenjualans::route('/'),
            'create' => Pages\CreateTransaksiPenjualan::route('/create'),
            'edit' => Pages\EditTransaksiPenjualan::route('/{record}/edit'),
        ];
    }
}
