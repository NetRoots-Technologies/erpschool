<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'timeFrame'=>'required|string',
            'costCenter' => 'required|exists:departments,id',
            'category' => 'required|exists:b_category,id',
            'startDate'=>'required|date|after_or_equal:today',
            'endDate'     => 'nullable|required_if:timeFrame,custom|date|after_or_equal:startDate',
            'amount' => 'required|numeric|min:0',
        ];
    }
}
