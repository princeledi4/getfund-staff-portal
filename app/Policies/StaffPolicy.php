<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StaffPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only authorized GetFund users with verified email can view staff
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Staff $staff): bool
    {
        // Only authorized GetFund users with verified email can view staff
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only authorized GetFund users with verified email can create staff
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Staff $staff): bool
    {
        // Only authorized GetFund users with verified email can update staff
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Staff $staff): bool
    {
        // Only authorized GetFund users with verified email can delete staff
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Staff $staff): bool
    {
        // Only authorized GetFund users with verified email can restore staff
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Staff $staff): bool
    {
        // Only authorized GetFund users with verified email can force delete staff
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }
}
