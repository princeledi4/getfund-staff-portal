<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class StaffImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    protected $errors = [];
    protected $failures = [];

    /**
     * Transform each row into a Staff model
     */
    public function model(array $row)
    {
        // Find department by ID or name
        $department = null;
        if (!empty($row['department_id'])) {
            $department = Department::find($row['department_id']);
        } elseif (!empty($row['department'])) {
            $department = Department::where('name', $row['department'])->first();
        }

        return new Staff([
            'staff_id' => $row['staff_id'] ?? null,
            'first_name' => $row['first_name'] ?? null,
            'middle_name' => $row['middle_name'] ?? null,
            'last_name' => $row['last_name'] ?? null,
            'email' => $row['email'] ?? null,
            'phone_number' => $row['phone_number'] ?? null,
            'position' => $row['position'] ?? null,
            'employment_type' => $row['employment_type'] ?? 'Full-time',
            'date_joined' => !empty($row['date_joined']) ? Carbon::parse($row['date_joined']) : null,
            'department_id' => $department?->id,
            'location' => $row['location'] ?? null,
            'status' => $row['status'] ?? 'active',
            'valid_until' => !empty($row['valid_until']) ? Carbon::parse($row['valid_until']) : Carbon::now()->addYear(),
        ]);
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'staff_id' => 'required|unique:staff,staff_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'staff_id.required' => 'Staff ID is required.',
            'staff_id.unique' => 'This Staff ID already exists.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.email' => 'Invalid email format.',
        ];
    }

    /**
     * Handle errors during import
     */
    public function onError(\Throwable $error)
    {
        $this->errors[] = $error->getMessage();
    }

    /**
     * Handle validation failures
     */
    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    /**
     * Get all errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get all failures
     */
    public function getFailures(): array
    {
        return $this->failures;
    }
}
