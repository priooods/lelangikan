<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterNelayanResource\Pages;
use App\Filament\Resources\MasterNelayanResource\RelationManagers;
use App\Models\MasterNelayan;
use App\Models\MIkanTabs;
use App\Models\MNelayanTab;
use App\Models\TIkanTabs;
use App\Models\TNelayanDetailTabs;
use App\Models\TNelayanTabs;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterNelayanResource extends Resource
{
    protected static ?string $model = MNelayanTab::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Nelayan';
    protected static ?string $breadcrumb = "Nelayan";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Nelayan')->placeholder('Masukan Nama Nelayan')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->searchable()->emptyStateHeading('Belum ada data nelayan')
            ->columns([
                TextColumn::make('name')->label('Nama Nelayan')->searchable(),
                TextColumn::make('m_status_tabs_id')->label('Status')->badge()->color(fn(string $state): string => match ($state) {
                    'Active' => 'success',
                    'Not Active' => 'danger',
                })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Tidak Ada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('activated')
                        ->label('Aktivasi')
                        ->action(function ($record) {
                            $record->update([
                                'm_status_tabs_id' => 8,
                            ]);
                        })
                        ->visible(fn($record) => $record->m_status_tabs_id === 9)
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Aktifkan data Nelayan')
                        ->modalDescription('Apakah anda ingin mengaktifkan Nelayan ?')
                        ->modalSubmitActionLabel('Aktifkan')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                    Action::make('unactivated')
                        ->label('NonAktif')
                        ->action(function ($record) {
                            $record->update([
                                'm_status_tabs_id' => 9,
                            ]);
                        })
                        ->visible(fn($record) => $record->m_status_tabs_id === 8)
                        ->icon('heroicon-o-check')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Non Aktifkan data Nelayan')
                        ->modalDescription('Apakah anda ingin nonaktifkan Nelayan ?')
                        ->modalSubmitActionLabel('Non Aktifkan')
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
            'index' => Pages\ListMasterNelayans::route('/'),
            'create' => Pages\CreateMasterNelayan::route('/create'),
            'edit' => Pages\EditMasterNelayan::route('/{record}/edit'),
        ];
    }
}
