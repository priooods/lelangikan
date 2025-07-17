<?php

namespace App\Filament\Pages;

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
                        ->required(),
                    TextInput::make('email')
                        ->required(),
                    TextInput::make('nik')
                        ->numeric(),
                    TextInput::make('phone')
                        ->numeric(),
                    TextInput::make('age')->label('Usia')
                        ->numeric(),
                    Textarea::make('address')->label('Alamat'),
                ])
            ])
            ->statePath('data')
            ->model(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('Update')
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
