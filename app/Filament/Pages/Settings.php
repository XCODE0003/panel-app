<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use App\Models\Chat;
use App\Models\User;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Настройки';
    protected static ?string $title = 'Настройки';
    protected static ?string $modelLabel = 'Настройки';
    protected static ?string $pluralLabel = 'Настройки';
    protected static string $view = 'filament.pages.settings';


    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }
    public $procent_profit;
    public $data;
    public function mount()
    {
        $this->data = Setting::first()->toArray();
    }

    public function save()
    {
        $setting = Setting::first();
        $setting->procent_profit = $this->data['procent_profit'];
        $setting->user_id_botProfit = $this->data['user_id_botProfit'];
        $setting->group_id_botProfit = $this->data['group_id_botProfit'];
        $setting->save();
        Notification::make()
            ->title('Настройки сохранены')
            ->success()
            ->send();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('procent_profit')
                    ->label('Процент прибыли')
                    ->required()
                    ->numeric()
                    ->step(1),
                Select::make('user_id_botProfit')
                    ->options(User::all()->pluck('login', 'id'))
                    ->label('От кого отправлять сообщения в канал с профитами')
                    ->required(),
                Select::make('group_id_botProfit')
                    ->options(Chat::where('is_group', true)->pluck('name', 'id'))
                    ->label('В какой канал отправлять сообщения с профитами')
                    ->required()
            ])
            ->statePath('data');
    }
}