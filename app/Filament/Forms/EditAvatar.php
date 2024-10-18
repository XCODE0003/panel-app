<?php

namespace App\Filament\Forms;

use Filament\Forms;
use Filament\Forms\Form;
use Livewire\Component;
use Livewire\WithFileUploads;

class AvatarUploadForm extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use WithFileUploads;

    public $avatar;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar')
                    ->image()
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('300')
                    ->imageResizeTargetHeight('300')
                    ->directory('avatars')
                    ->visibility('public')
                    ->maxSize(1024)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->required(),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        auth()->user()->update([
            'avatar_url' => $data['avatar'],
        ]);

        $this->dispatch('avatar-updated');
    }

    public function render()
    {
        return view('filament.forms.avatar-upload-form');
    }
}
