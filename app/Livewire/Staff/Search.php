<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Search extends Component
{
    #[Rule('required')]
    #[Rule('max:20')]
    public $staff_id;

    public function searchStaff()
    {
        // validate the input
        $this->validate();

        // search for the staff
        $staff = Staff::where('staff_id', $this->staff_id)->first();

        // return an error message and pass it to the validation laravel error bag
        if (!$staff) {
            $this->addError('staff_id', 'The provided Staff Id does not match our records. Please try again.');
        }

        // redirect to the staff profile page
        return redirect()->route('staff.profile', $staff->staff_id);
    }

    public function render()
    {
        return view('livewire.staff.search');
    }
}
