<?php

namespace App\Services\Filament;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Filament\Widgets\AccountOverview;
use App\Filament\Widgets\NewsWidget;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function getColumns(): int | string | array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            AccountOverview::class,
            NewsWidget::class,
        ];
    }
}