<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only authorized GetFund users with verified email can view departments
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Department $department): bool
    {
        // Only authorized GetFund users with verified email can view departments
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only authorized GetFund users with verified email can create departments
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Department $department): bool
    {
        // Only authorized GetFund users with verified email can update departments
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Department $department): bool
    {
        // Only authorized GetFund users with verified email can delete departments
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Department $department): bool
    {
        // Only authorized GetFund users with verified email can restore departments
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Department $department): bool
    {
        // Only authorized GetFund users with verified email can force delete departments
        return str_ends_with($user->email, '@getfund.gov.gh') && $user->hasVerifiedEmail();
    }
}
