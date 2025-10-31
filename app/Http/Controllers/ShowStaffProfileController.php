<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ShowStaffProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $staff_id): View
    {
        $staff = Staff::query()
            ->with(['department:id,name'])
            ->where('staff_id', $staff_id)
            ->firstOrFail();

        // Log staff profile access for security monitoring
        Log::info('Staff profile accessed', [
            'staff_id' => $staff_id,
            'staff_name' => $staff->fullname,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);

        return view('staff-details', compact('staff'));
    }
}
