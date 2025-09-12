<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;

    protected $table = 'account_types';

    protected $fillable = [
        'name',
        'code',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    static public function getActiveListDropdown($get_empty_option = false)
    {
        $data = self::where(['status' => 1])->OrderBy('name', 'asc')->select('name', 'code', 'id')->get()->getDictionary();
        $result = array();
        if ($get_empty_option) {
            $result[''] = 'Select an Account Type';
        }
        if ($data) {
            foreach ($data as $record) {
                $result[$record->id] = ($record->code) ? $record->code . ' - ' . $record->name : $record->name;
            }
        }
        return $result;
    }

}
