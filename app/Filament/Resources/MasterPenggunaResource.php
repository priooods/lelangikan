<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterPenggunaResource\Pages;
use App\Filament\Resources\MasterPenggunaResource\RelationManagers;
use App\Models\MasterPengguna;
use App\Models\PegawaiTabs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterPenggunaResource extends Resource
{
    protected static ?string $model = PegawaiTabs::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Pegawai';
    protected static ?string $breadcrumb = "Pegawai";
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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMasterPenggunas::route('/'),
            'create' => Pages\CreateMasterPengguna::route('/create'),
            'edit' => Pages\EditMasterPengguna::route('/{record}/edit'),
        ];
    }
}
