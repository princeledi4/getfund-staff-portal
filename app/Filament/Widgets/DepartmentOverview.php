<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DepartmentOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Departments', Department::count())
                ->description('Total number of departments')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
