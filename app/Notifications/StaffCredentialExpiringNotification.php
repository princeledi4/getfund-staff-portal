<?php

namespace App\Notifications;

use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffCredentialExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Staff $staff,
        public int $daysUntilExpiry
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $urgency = $this->daysUntilExpiry <= 7 ? 'URGENT' : 'Notice';

        return (new MailMessage)
            ->subject("{$urgency}: Staff Credential Expiring Soon")
            ->greeting("Hello!")
            ->line("This is a notification that credentials for the following staff member are expiring soon:")
            ->line("**Name:** {$this->staff->fullname}")
            ->line("**Staff ID:** {$this->staff->staff_id}")
            ->line("**Department:** {$this->staff->department->name}")
            ->line("**Expiry Date:** {$this->staff->valid_until->format('d F Y')}")
            ->line("**Days Remaining:** {$this->daysUntilExpiry} days")
            ->action('View Staff Details', url("/admin/staff/{$this->staff->id}"))
            ->line('Please take necessary action to renew the credentials before they expire.')
            ->line('Thank you for using GetFund Staff Portal!');
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'staff_id' => $this->staff->id,
            'staff_name' => $this->staff->fullname,
            'staff_number' => $this->staff->staff_id,
            'expiry_date' => $this->staff->valid_until->toDateString(),
            'days_remaining' => $this->daysUntilExpiry,
            'message' => "Credentials for {$this->staff->fullname} expire in {$this->daysUntilExpiry} days",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'staff_id' => $this->staff->id,
            'days_remaining' => $this->daysUntilExpiry,
        ];
    }
}
