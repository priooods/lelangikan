<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterPenggunaResource\Pages;
use App\Filament\Resources\MasterPenggunaResource\RelationManagers;
use App\Models\MasterPengguna;
use App\Models\MUserRoleTabs;
use App\Models\PegawaiTabs;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class MasterPenggunaResource extends Resource
{
    protected static ?string $model = PegawaiTabs::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Pegawai';
    protected static ?string $breadcrumb = "Pegawai";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->guard('admin')->user()->m_user_role_tabs_id == 1) return true;
        else return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')->label('Nama')->placeholder('Masukan Nama Pengguna')->required(),
            TextInput::make('email')->label('Email')->required(),
            Select::make('m_user_role_tabs_id')
                ->label('Pilih Role')
                ->placeholder('Cari Role')
                ->options(MUserRoleTabs::whereNotIn('id', [1, 3])->pluck('title', 'id'))
                ->getSearchResultsUsing(fn(string $search): array => MUserRoleTabs::whereNotIn('id', [1, 3])->where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                ->getOptionLabelUsing(fn($value): ?string => MUserRoleTabs::find($value)?->title)
                ->default(0)
                ->required(),
            Select::make('gender')
                ->label('Pilih Gender')
                ->placeholder('Pilih Gender')
                ->options([
                    0 => 'Wanita',
                    1 => 'Pria',
                ])
                ->native(false)
                ->default(1)
                ->required(),
            TextInput::make('password')->label('Password Akun')
                ->password()->revealable()
                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                ->same('passwordConfirmation')
                ->placeholder('Masukan Password')
                ->dehydrated(fn(?string $state): bool => filled($state))
                ->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state('');
                }),
            TextInput::make('passwordConfirmation')->label('Confirmasi Password Akun')->password()->revealable()->placeholder('Masukan Password')->required(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                PegawaiTabs::whereNot('m_user_role_tabs_id', 1)
            )
            ->searchable()
            ->emptyStateHeading('Belum ada data pengguna')
            ->columns([
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('email')->label('Email'),
            TextColumn::make('m_user_role_tabs_id')->label('Role')
                ->getStateUsing(fn($record) => $record->role ? $record->role?->title : '-')->alignment(Alignment::Center),
            TextColumn::make('gender')->label('Gender')->getStateUsing(fn($record) => $record === 1 ? 'Pria' : 'Wanita')->alignment(Alignment::Center),
            ])
            ->filters([
                //
            ])
            ->actions([
            Tables\Actions\EditAction::make()
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
