<?php

namespace App\Http\Requests\Sensor;

use App\Repositories\SensorRepository;
use Illuminate\Foundation\Http\FormRequest;

class StoreSensorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:active,inactive'],
            'location_id' => ['required', 'uuid', 'exists:locations,id'],
            'name' => ['required', 'string', 'max:50'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->has('location_id')) {
                return;
            }

            $repository = app(SensorRepository::class);

            $sensors = $repository->get([], [
                'location_id' => request()->input('location_id'),
                'name' =>  request()->input('name'),
            ]);

            if ($sensors->count() > 0) {
                $validator->errors()->add('name', 'The name has already been taken for this location.');
            }
        });
    }
}
