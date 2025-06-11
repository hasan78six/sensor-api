<?php

namespace App\Http\Requests\Visitor;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVisitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sensor_id'   => ['bail', 'required', 'uuid', 'exists:sensors,id'],
            'date'        => [
                'bail',
                'required',
                'date_format:Y-m-d',
                Rule::unique('visitors')->where(function ($query) {
                    return $query->where('sensor_id', request()->input('sensor_id'));
                }),
            ],
            'count'       => ['required', 'integer', 'min:0'],
        ];
    }
}
