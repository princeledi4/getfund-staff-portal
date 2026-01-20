<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\User;
use App\Notifications\StaffCredentialExpiringNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staff:send-expiry-notifications {--days=30 : Number of days before expiry to send notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for staff credentials expiring soon';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');

        $this->info("Checking for staff credentials expiring in the next {$days} days...");

        // Get all admins to notify
        $admins = User::all();

        if ($admins->isEmpty()) {
            $this->warn('No admin users found to send notifications to.');
            return Command::FAILURE;
        }

        $expiringStaff = Staff::query()
            ->whereNotNull('valid_until')
            ->where('valid_until', '>', now())
            ->where('valid_until', '<=', now()->addDays($days))
            ->with('department')
            ->get();

        if ($expiringStaff->isEmpty()) {
            $this->info('No staff with expiring credentials found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$expiringStaff->count()} staff with expiring credentials.");

        $bar = $this->output->createProgressBar($expiringStaff->count());
        $bar->start();

        $sent = 0;

        foreach ($expiringStaff as $staff) {
            $daysUntilExpiry = (int) now()->diffInDays($staff->valid_until, false);

            // Only send notifications for specific milestones: 30, 14, 7, 3, 1 days
            if (in_array($daysUntilExpiry, [30, 14, 7, 3, 1]) || $daysUntilExpiry <= 0) {
                foreach ($admins as $admin) {
                    $admin->notify(new StaffCredentialExpiringNotification($staff, $daysUntilExpiry));
                }
                $sent++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✓ Sent {$sent} notifications successfully!");

        return Command::SUCCESS;
    }
}
