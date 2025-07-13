<?php

namespace App\Filament\Pelanggan\Resources;

use App\Filament\Pelanggan\Resources\PasarIkanResource\Pages;
use App\Filament\Pelanggan\Resources\PasarIkanResource\RelationManagers;
use App\Models\PasarIkan;
use App\Models\TPenjualanTabs;
use App\Models\TPenjualanTransactionTabs;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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

class PasarIkanResource extends Resource
{
    protected static ?string $model = TPenjualanTabs::class;
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Beli Ikan';
    protected static ?string $breadcrumb = "Beli";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                TPenjualanTabs::with('transaction')->whereHas('transaction', function($a){
                    $a->whereNotIn('m_status_tabs_id',[4,6]);
                })
            )
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
                TextColumn::make('transaction')->label('Status')->alignment(Alignment::Center)->badge()->color(fn(string $state): string => match ($state) {
                    'Tersedia' => 'success',
                })->getStateUsing(fn($record) => 'Tersedia'),
            ])
            ->filters([
                //
            ])->recordUrl(null)
            ->actions([
            Action::make('payment')
                ->label('Beli Ikan')
                ->action(function (array $data, $record) {
                    TPenjualanTransactionTabs::create([
                        't_penjualan_tabs_id' => $record->id,
                        'users_id' => auth()->user()->id,
                        'm_status_tabs_id' => 4,
                        'count' => 1,
                        'notes' => $data['notes'],
                        'payment_path' => $data['payment_path'],
                    ]);
                })
                ->form([
                    FileUpload::make('payment_path')->label('Upload Bukti Bayar')
                        ->uploadingMessage('Uploading attachment...')
                        ->reorderable()
                        ->preserveFilenames()
                        ->image()
                        ->directory('foto-bayar')
                        ->maxSize(5000)->required(),
                    Textarea::make('notes')->label('Catatan Pembayaran')->placeholder('Masukan Catatan'),
                ])
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Apakah anda ingin membeli ikan ?')
                ->modalDescription('')
                ->modalSubmitActionLabel('Kirim Bukti Bayar')
                ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListPasarIkans::route('/'),
            'create' => Pages\CreatePasarIkan::route('/create'),
            'edit' => Pages\EditPasarIkan::route('/{record}/edit'),
        ];
    }
}
