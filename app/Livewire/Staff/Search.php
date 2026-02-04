<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Search extends Component
{
    #[Rule('required')]
    public $staff_id;

    #[Rule('required')]
    #[Rule('max:50')]
    public $surname;

    public function searchStaff()
    {
        // Trim whitespace from inputs
        $this->staff_id = trim($this->staff_id);
        $this->surname = trim($this->surname);

        // Check if fields are empty
        if (empty($this->staff_id) || empty($this->surname)) {
            $this->dispatch('show-error', message: 'Please enter both Staff ID and Surname.');
            return;
        }

        // SQL Injection Protection: Block dangerous SQL keywords at the start of surname
        $dangerousSqlKeywords = [
            'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'CREATE', 'ALTER',
            'TRUNCATE', 'EXEC', 'EXECUTE', 'UNION', 'DECLARE', 'CAST', 'CONVERT',
            'SCRIPT', 'JAVASCRIPT', 'XSS', 'SLEEP', 'WAITFOR', 'BENCHMARK'
        ];

        $surnameUpper = strtoupper($this->surname);
        foreach ($dangerousSqlKeywords as $keyword) {
            if (str_starts_with($surnameUpper, $keyword)) {
                $this->dispatch('show-error', message: 'Invalid surname format. Please enter a valid surname.');
                return;
            }
        }

        // Validate Staff ID format
        if (!preg_match('/^GF\d{8,9}$/', $this->staff_id)) {
            $this->dispatch('show-error', message: 'Invalid Staff ID format.');
            return;
        }

        // Validate surname length
        if (strlen($this->surname) > 50) {
            $this->dispatch('show-error', message: 'Surname is too long. Maximum 50 characters.');
            return;
        }

        // search for the staff by staff_id AND surname (last_name) - case insensitive
        $staff = Staff::where('staff_id', $this->staff_id)
                      ->whereRaw('LOWER(last_name) = ?', [strtolower($this->surname)])
                      ->first();

        // return an error message if staff not found
        if (!$staff) {
            $this->dispatch('show-error', message: 'Either the Staff ID or Surname is incorrect. Please check and try again.');
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
