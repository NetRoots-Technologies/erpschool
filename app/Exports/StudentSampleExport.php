<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentSampleExport implements FromArray, WithHeadings
{
    public function array(): array
{
    return [
        [
            'Grade 5', 'Main Campus', 'STD001', 'CLS-A', 'SEC-B', 'First1', 'Last1', 'Father1', '35201-1234561-1',
            'Guardian1', '35202-9876541-1', 'Male', '2015-01-01', '2025-06-01', 'Address 1', 'Permanent 1',
            'Lahore', 'Pakistan', '03001234561', '04235678901', 'student1@mail.com',
            'Urdu', 'English', 'Punjabi', 'School1', 'National School System', 'Reason 1',
            'Emergency1', 'Parent', 'Lahore', '04212345671', '03012345671', 'emergency1@mail.com',
            'Self Pickup', 'No', 'Yes', 'Yes'
        ]
    ];
}


public function headings(): array
{
    return [
        'Admission Class', 'Campus', 'Student ID', 'Class', 'Section', 'First Name', 'Last Name', 'Father Name', 'Father CNIC',
        'Guardian Name', 'Guardian CNIC', 'Gender', 'Date of Birth', 'Admission Date', 'Current Address', 'Permanent Address',
        'City', 'Country', 'Cell No', 'Landline', 'Email', 'Native Language', 'First Language', 'Second Language',
        'School Name', 'School Origin', 'Leaving Reason', 'Emergency Contact Name', 'Emergency Relationship',
        'Emergency City', 'Emergency Landline', 'Emergency Cell No', 'Emergency Email', 'Transport Mode',
        'School Transport', 'Meal Option', 'Photo Permission'
    ];
}

}
