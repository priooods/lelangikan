<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterIkanResource\Pages;
use App\Filament\Resources\MasterIkanResource\RelationManagers;
use App\Models\MasterIkan;
use App\Models\MIkanTabs;
use App\Models\MNelayanTab;
use App\Models\TIkanTabs;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterIkanResource extends Resource
{
    protected static ?string $model = TIkanTabs::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Ikan';
    protected static ?string $breadcrumb = "Ikan";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('m_nelayan_tabs_id')
                    ->label('Nama Nelayan')
                    ->placeholder('Cari Nama Nelayan')
                    ->relationship('nelayan', 'name')
                    ->createOptionForm([
                        TextInput::make('name')->label('Nama Nelayan')->placeholder('Masukan Nama Nelayan')->required(),
                    ])->createOptionUsing(function (array $data) {
                        $data['m_status_tabs_id'] = 8;
                        MNelayanTab::create($data);
                        return $data['name'];
                    })
                    ->options(
                        MNelayanTab::query()->where('m_status_tabs_id',8)->pluck('name', 'id'),
                    )
                    ->searchable()
                    ->required(),
            Select::make('m_ikan_tabs_id')
                    ->label('Nama Ikan')
                    ->placeholder('Cari Nama Ikan')
                    ->relationship('ikan', 'fish_name')
                    ->createOptionForm([
                        TextInput::make('fish_name')->label('Nama Ikan')->placeholder('Masukan Nama Ikan')->required(),
                        FileUpload::make('fish_picture')->label('Upload Foto Ikan')
                            ->uploadingMessage('Uploading attachment...')
                            ->reorderable()
                            ->preserveFilenames()
                            ->image()
                            ->directory('foto-ikan')
                            ->maxSize(5000)->required()
                    ])
                    ->options(
                        MIkanTabs::query()->pluck('fish_name', 'id'),
                    )
                    ->searchable()
                    ->required(),
                TextInput::make('count')->label('Jumlah Ikan')->numeric()->placeholder('Masukan Jumlah Ikan')->required(),
                TextInput::make('weight')->label('Berat Total Ikan (Kg)')->numeric()->placeholder('Masukan Berat Total')->required(),
                Textarea::make('description')->label('Deskripsi')->placeholder('Masukan Deskripsi'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchable()
            ->emptyStateHeading('Belum ada data ikan')
            ->columns([
                TextColumn::make('m_ikan_tabs_id.fish_name')->label('Nama Ikan')->searchable()->getStateUsing(fn($record) => $record->ikan ? $record->ikan->fish_name : '-'),
                TextColumn::make('m_nelayan_tabs_id')->label('Nelayan')->searchable()->getStateUsing(fn($record) => $record->nelayan ? $record->nelayan->name : '-'),
                TextColumn::make('count')->label('Jumlah')->alignment(Alignment::Center),
                TextColumn::make('weight')->label('Berat (Kg)')->alignment(Alignment::Center),
                ImageColumn::make('m_ikan_tabs_id')->label('Foto')->alignment(Alignment::Center)->getStateUsing(function (TIkanTabs $record): string {
                    return $record->ikan->fish_picture;
                }),
                TextColumn::make('description')->label('Deskripsi')->words(5),
                TextColumn::make('created_at')->label('Time In')->dateTime()->alignment(Alignment::Center),
            ])
            ->filters([
            Filter::make('created_at')
                ->form([
                    DatePicker::make('start_date')->label('Dari Tanggal')->format('Y-m-d'),
                    DatePicker::make('start_end')->label('Sampai Tanggal')->format('Y-m-d'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['start_date'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['start_end'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
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
            'index' => Pages\ListMasterIkans::route('/'),
            'create' => Pages\CreateMasterIkan::route('/create'),
            'edit' => Pages\EditMasterIkan::route('/{record}/edit'),
        ];
    }
}
