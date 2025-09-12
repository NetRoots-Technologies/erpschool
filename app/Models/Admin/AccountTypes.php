<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTypes extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ['name', 'code', 'status', 'created_by', 'updated_by' . 'deleted_by'];

    protected $table = 'account_types';

    static public function pluckActiveOnly()
    {
        return self::where(['status' => 1])->OrderBy('name', 'asc')->pluck('name', 'id');
    }

    static public function getActiveList()
    {
        $data = self::where(['status' => 1])->OrderBy('name', 'asc')->select('name', 'code', 'id')->get()->getDictionary();
        $result = array();
        if ($data) {
            foreach ($data as $record) {
                $result[$record->id] = array(
                    'id' => $record->id,
                    'name' => $record->name,
                    'code' => $record->code,
                );
            }
        }
        return $result;
    }

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
