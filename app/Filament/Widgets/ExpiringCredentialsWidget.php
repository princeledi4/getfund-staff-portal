<?php

namespace App\Filament\Widgets;

use App\Models\Staff;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExpiringCredentialsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $now = now();
        $next30Days = now()->addDays(30);
        $next60Days = now()->addDays(60);

        // Expiring in next 30 days
        $expiring30 = Staff::query()
            ->whereNotNull('valid_until')
            ->whereBetween('valid_until', [$now, $next30Days])
            ->count();

        // Expiring in next 60 days
        $expiring60 = Staff::query()
            ->whereNotNull('valid_until')
            ->whereBetween('valid_until', [$now, $next60Days])
            ->count();

        // Already expired
        $expired = Staff::query()
            ->whereNotNull('valid_until')
            ->where('valid_until', '<', $now)
            ->count();

        // Pending verification (not verified in 90 days)
        $pendingVerification = Staff::query()
            ->where(function($query) use ($now) {
                $query->whereNull('last_verified')
                    ->orWhere('last_verified', '<', $now->copy()->subDays(90));
            })
            ->count();

        return [
            Stat::make('Expired Credentials', $expired)
                ->description('Credentials already expired')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make('Expiring Soon (30 days)', $expiring30)
                ->description('Action required within 30 days')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Expiring (60 days)', $expiring60)
                ->description('Renewals needed within 60 days')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('info'),

            Stat::make('Pending Verification', $pendingVerification)
                ->description('Not verified in 90+ days')
                ->descriptionIcon('heroicon-o-shield-exclamation')
                ->color('warning'),
        ];
    }
}
