<?php

namespace App\Imports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class MedicineImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $data = [];
        
        // Loop through the $row data and filter out empty values
        foreach ($row as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $value;
            }
        }
        return new Medicine([
            'registeration_number' => $row['registeration_number'],
            'registeration_year' => $row['registeration_year'],
            'target' => $row['target'],
            'type' => $row['type'],
            'branch' => $row['branch'],
            'scientific_name' => $row['scientific_name'],
            'commercial_name' => $row['commercial_name'],
            'dose' => $row['dose'],
            'dose_unit' => $row['dose_unit'],
            'pharmaceutical_form' => $row['pharmaceutical_form'],
            'route' => $row['route'],
            'code1' => $row['code1'],
            'code2' => $row['code2'],
            'size' => $row['size'],
            'size_unit' => $row['size_unit'],
            'package_type' => $row['package_type'],
            'package_size' => $row['package_size'],
            'prescription_method' => $row['prescription_method'],
            'control' => $row['control'],
            'marketing_company_name' => $row['marketing_company_name'],
            'representative' => $row['representative'],
        ]);
    }
}
