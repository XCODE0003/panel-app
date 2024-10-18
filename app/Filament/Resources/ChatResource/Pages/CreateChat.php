<?php

namespace App\Filament\Resources\ChatResource\Pages;

use App\Filament\Resources\ChatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentView;

use Throwable;
use Halt;

class CreateChat extends CreateRecord
{
    protected static string $resource = ChatResource::class;
}
