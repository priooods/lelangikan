<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterPelangganResource\Pages;
use App\Filament\Resources\MasterPelangganResource\RelationManagers;
use App\Models\MasterPelanggan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterPelangganResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $breadcrumb = "Pelanggan";
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
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('email')->label('Email'),
            TextColumn::make('gender')->label('Gender')->getStateUsing(fn($record) => $record === 1 ? 'Pria' : 'Wanita')->alignment(Alignment::Center),
            TextColumn::make('alamat')->label('Alamat'),
            ])
            ->filters([
                //
            ])
            ->actions([
            Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMasterPelanggans::route('/'),
            'create' => Pages\CreateMasterPelanggan::route('/create'),
            'edit' => Pages\EditMasterPelanggan::route('/{record}/edit'),
        ];
    }
}
