<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return [
            'staff_id',
            'first_name',
            'middle_name',
            'last_name',
            'position',
            'department_id',
            'department',
            'employment_type',
            'date_joined',
            'status',
            'valid_until',
            'email',
            'phone_number',
            'location',
        ];
    }

    public function array(): array
    {
        // Sample data rows to help users understand the format
        return [
            [
                'GF001',
                'John',
                'Kwame',
                'Doe',
                'Manager',
                '1',
                'Administration',
                'Full-time',
                '2024-01-15',
                'active',
                '2025-01-15',
                'john.doe@example.com',
                '0201234567',
                'Accra',
            ],
            [
                'GF002',
                'Jane',
                '',
                'Smith',
                'Officer',
                '2',
                'Finance',
                'Contract',
                '2024-02-01',
                'active',
                '2025-02-01',
                'jane.smith@example.com',
                '0209876543',
                'Kumasi',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Add instructions row
                $departments = Department::pluck('name', 'id')->toArray();
                $deptList = collect($departments)->map(fn($name, $id) => "{$id}={$name}")->implode(', ');

                $instructions = [
                    'INSTRUCTIONS: Delete these instruction rows before importing.',
                    'Required fields: staff_id, first_name, last_name',
                    'department_id OR department name can be used. Available departments: ' . ($deptList ?: 'None - create departments first'),
                    'employment_type options: Full-time, Part-time, Contract, Intern',
                    'status options: active, inactive, suspended',
                    'Date format: YYYY-MM-DD (e.g., 2024-01-15)',
                ];

                // Insert instruction rows at the end
                $lastRow = $sheet->getHighestRow() + 2;
                foreach ($instructions as $index => $instruction) {
                    $sheet->setCellValue('A' . ($lastRow + $index), $instruction);
                    $sheet->getStyle('A' . ($lastRow + $index))->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF666666'));
                }
            },
        ];
    }
}
