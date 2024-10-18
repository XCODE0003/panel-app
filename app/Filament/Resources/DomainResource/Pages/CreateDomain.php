<?php

namespace App\Filament\Resources\DomainResource\Pages;

use App\Filament\Resources\DomainResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;

class CreateDomain extends CreateRecord
{

    protected static string $resource = DomainResource::class;
    protected static ?string $title = 'Добавить домен';
    protected static bool $canCreateAnother = false;
    public function getBreadcrumb(): string
    {
        return "Привязать домен";
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Привязать домен')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }
}
