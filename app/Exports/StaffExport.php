<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Staff::with('department')->get();
    }

    /**
     * Define the column headings
     */
    public function headings(): array
    {
        return [
            'Staff ID',
            'Full Name',
            'First Name',
            'Middle Name',
            'Last Name',
            'Department',
            'Position',
            'Employment Type',
            'Status',
            'Phone Number',
            'Email',
            'Location',
            'Date Joined',
            'Security Clearance',
            'Last Verified',
            'Valid Until',
            'Background Check',
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($staff): array
    {
        return [
            $staff->staff_id,
            $staff->fullname,
            $staff->first_name,
            $staff->middle_name,
            $staff->last_name,
            $staff->department->name ?? 'N/A',
            $staff->position ?? 'N/A',
            $staff->employment_type ?? 'N/A',
            $staff->status ?? 'Active',
            $staff->phone_number ?? 'N/A',
            $staff->email ?? 'N/A',
            $staff->location ?? 'N/A',
            $staff->date_joined?->format('d/m/Y') ?? 'N/A',
            $staff->security_clearance ?? 'N/A',
            $staff->last_verified?->format('d/m/Y') ?? 'N/A',
            $staff->valid_until?->format('d/m/Y') ?? 'N/A',
            $staff->background_check ? 'Completed' : 'Pending',
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
