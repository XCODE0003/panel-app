<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Services\Ton\WalletGenerator;

class AccountOverview extends Widget
{
    protected static string $view = 'filament.widgets.account-overview';

    public function getBalance(): int
    {

        return auth()->user()->balance();
    }
}