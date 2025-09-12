<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Applicant Name',
            'Father Name',
            'CNIC',
            'Mobile No',
            'Emergency Contact Number',
            'Personal Email Address',
            'Professional Email Address',
            'Password',
            'Present Address',
            'Permanent Address',
            'Date of Birth',
            'Gender',
            'Date of Joining',
            'Institution',
            'Year',
            'Certification/Degree',
            'CGPA',
            'Specialization',
            'Company Name',
            'Branch Name',
            'Department',
            'Designation',
            'Work Shift',
            'Report To',
            'Job Type',
            'Applied before at cornerstone?',
            'Were you ever employed here?',
            'Engagements in any other business or employment?',
            'Skills or training related to the position?'
        ];
    }

    public function array(): array
    {
        // Example dummy data for export (must match number of columns in headings)
        return [
            [
                'Ali Khan', 'Ahmed Khan', '35202-1234567-8', '03001234567', '03007654321',
                'ali@example.com', 'ali@company.com', 'secret', 'Lahore', 'Multan',
                '1995-01-01', 'Male', '2022-05-01', 'PU', '2017', 'BSCS', '3.5', 'Software Engineering',
                'Cornerstone Ltd', 'Main Branch', 'IT', 'Developer', 'Morning', 'Manager Name', 'Full-time',
                'No', 'No', 'No', 'Laravel, React, MySQL'
            ]
        ];
    }
}
