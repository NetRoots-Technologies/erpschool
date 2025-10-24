<?php

namespace App\Imports;

use App\Models\Fee\FeeCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;

class FeeCategoryImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    use Importable;

    protected $headersChecked = false;

    public function model(array $row)
    {
        
        if (!$this->headersChecked) {
            $this->headersChecked = true;

            if (count($row) > 6) {
                throw new \Exception("Excel file headers should not be more than 6 columns!");
            }
        }

        if(!$row["category_name"]){
             throw new \Exception("Category Name is Empty");
        }

        if(!$row["category_type"]){
             throw new \Exception("Category Type is Empty");
        }

       
        $allowedTypes = ['monthly', 'one_time', 'admission', 'annual'];

        if (!in_array(strtolower($row["category_type"]), $allowedTypes)) {
            throw new \Exception("Invalid Category Type: {$row["category_type"]}. Allowed types: monthly, one_time, admission, annual");
        }


        $feeCategory = new FeeCategory;
        $feeCategory->name = $row["category_name"];
        $feeCategory->type = $row["category_type"];
        $feeCategory->description = $row["description"] ?? NULL;
        $feeCategory->is_mandatory = !empty($row["mandatory_fee"]) && $row["mandatory_fee"] == 'Yes' ? 1 : 0;
        $feeCategory->affects_financials = !empty($row["affects_financials"]) && $row["affects_financials"] == 'Yes' ? 1 : 0;
        $feeCategory->is_active = !empty($row["active"]) && $row["active"] == 'Active' ? 1 : 0;
        $feeCategory->created_at = auth()->user()->id;
        $feeCategory->updated_at = auth()->user()->id;
        $feeCategory->save();

        return $feeCategory;
    }
}
