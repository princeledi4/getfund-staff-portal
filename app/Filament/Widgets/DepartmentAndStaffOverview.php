<?php

namespace App\Filament\Widgets;

use App\Models\Staff;
use App\Models\Department;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DepartmentAndStaffOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Departments', Department::count())
                ->description('Total number of departments')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Staffs', Staff::count())
                ->description('Total number of staff')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
