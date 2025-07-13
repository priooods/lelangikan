<?php

namespace App\Filament\Pelanggan\Resources;

use App\Filament\Pelanggan\Resources\LelangIkanResource\Pages;
use App\Filament\Pelanggan\Resources\LelangIkanResource\RelationManagers;
use App\Models\LelangIkan;
use App\Models\TLelangTabs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
            'index' => Pages\ListLelangIkans::route('/'),
            'create' => Pages\CreateLelangIkan::route('/create'),
            'edit' => Pages\EditLelangIkan::route('/{record}/edit'),
        ];
    }
}
