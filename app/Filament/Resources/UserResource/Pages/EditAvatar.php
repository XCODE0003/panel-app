<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditAvatar extends EditRecord
{

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar_url')
                    ->image()
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('300')
                    ->imageResizeTargetHeight('300')
                    ->directory('avatars')
                    ->visibility('public')
                    ->maxSize(1024)
                    ->label('Аватар')
            ]);
    }

    public function afterSave(): void
    {
        if ($oldAvatar = $this->record->getOriginal('avatar_url')) {
            Storage::disk('public')->delete($oldAvatar);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
