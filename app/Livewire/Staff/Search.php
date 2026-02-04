<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Search extends Component
{
    #[Rule('required', message: 'Please enter your Staff ID.')]
    #[Rule('max:20')]
    public $staff_id;

    #[Rule('required', message: 'Please enter your surname.')]
    #[Rule('max:255')]
    public $surname;

    public $showError = false;

    public function searchStaff()
    {
        // Clear previous error
        $this->showError = false;

        // validate the input
        $this->validate();

        // search for the staff by staff_id and surname (last_name)
        $staff = Staff::where('staff_id', $this->staff_id)
            ->whereRaw('LOWER(last_name) = ?', [strtolower($this->surname)])
            ->first();

        // show popup if not found
        if (!$staff) {
            $this->showError = true;
            return;
        }

        // redirect to the staff profile page
        return redirect()->route('staff.profile', $staff->staff_id);
    }

    public function render()
    {
        return view('livewire.staff.search');
    }
}
