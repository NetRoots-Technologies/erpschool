<?php

namespace App\Http\Controllers;

use App\Models\Admin\Branch;
use Illuminate\Http\Request;

class PrintableController extends Controller
{
    public function printPreview($tableName)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (!$tableName)
            abort(404);

        $table = config("printable.$tableName");
        $model = $table['model'];
        $fields = $table['fields'];
        $with = $table['with'] ?? [];
        $foreign_key = $table['foreign_keys'] ?? [];
        $columns = array_merge(array_filter(array_keys($fields), fn($x) => !str_contains($x, '.')), $foreign_key);
        $data = $model::select($columns)->with($with)->get();
        // $data=         Branch::with('company')->first();
        // return $foreign_key;
        // return array_diff($columns,$foreign_key,['id']);
        // return array_filter(array_keys($fields), fn($x) => !str_contains($x, '.'));
        $fields = array_diff($fields, ['id']);
        // return $fields;
        // return $data;
        return view('print-preview', compact('fields', 'data', 'tableName'));
    }
}
