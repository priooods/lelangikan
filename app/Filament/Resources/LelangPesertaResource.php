<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LelangPesertaResource\Pages;
use App\Filament\Resources\LelangPesertaResource\RelationManagers;
use App\Models\LelangPeserta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LelangPesertaResource extends Resource
{
    protected static ?string $model = LelangPeserta::class;
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
            'index' => Pages\ListLelangPesertas::route('/'),
            'create' => Pages\CreateLelangPeserta::route('/create'),
            'edit' => Pages\EditLelangPeserta::route('/{record}/edit'),
        ];
    }
}
