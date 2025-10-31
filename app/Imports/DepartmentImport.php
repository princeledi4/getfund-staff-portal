<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Str;

class DepartmentImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    protected $errors = [];
    protected $failures = [];

    /**
     * Transform each row into a Department model
     */
    public function model(array $row)
    {
        // Auto-generate slug if not provided
        $slug = !empty($row['slug']) ? $row['slug'] : Str::slug($row['name'] ?? '');

        return new Department([
            'name' => $row['name'] ?? null,
            'slug' => $slug,
            'description' => $row['description'] ?? null,
        ]);
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:departments,name',
            'slug' => 'nullable|string|max:255|unique:departments,slug',
            'description' => 'nullable|string',
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
