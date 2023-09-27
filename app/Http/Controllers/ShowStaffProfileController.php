<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowStaffProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $id): View
    {
        $staff = Staff::query()
            ->with(['department:id,name'])
            ->where('staff_id', $id)
            ->first();

        return view('staff-details', compact('staff'));
    }
}
