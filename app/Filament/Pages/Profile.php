<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Profile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.profile';
    public ?array $data = [];
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $this->form->fill(
            auth()->user()->attributesToArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->columns(2)->schema([
                    TextInput::make('name')
                        ->autofocus()
                    ->placeholder('Nama Pengguna')
                        ->required(),
                    TextInput::make('email')
                    ->placeholder('Email Pengguna')
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
                Textarea::make('address')->label('Alamat')->placeholder('Alamat lengkap'),
                ])
            ])
            ->statePath('data')
            ->model(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Update')
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update()
    {
        auth()->user()->update(
            $this->form->getState()
        );
    }
}
