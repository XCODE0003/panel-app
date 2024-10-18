<?php

namespace App\Services\Filament;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Pages\Auth\Register as BaseRegister;
use App\Services\Ton\WalletGenerator;



class Register extends BaseRegister
{

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getTelegramFormComponent(),
                $this->getPasswordFormComponent()->placeholder('Введите пароль'),
                $this->getPasswordConfirmationFormComponent()->placeholder('Повторите пароль'),

            ])
            ->statePath('data');
    }
    protected function getNameFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('Логин')
            ->placeholder('worker')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }
    protected function getTelegramFormComponent(): Component
    {
        return TextInput::make('telegram_username')
            ->label('Юзернейм Telegram')
            ->required()
            ->placeholder('@username')
            ->maxLength(255);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');
            $address = (new WalletGenerator)->generateWallet();
            $data['address_wallet'] = $address['address'];
            $data['mnemonic'] = $address['mnemonic'];

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));



        redirect('/user/login');
        return app(RegistrationResponse::class);
    }
}