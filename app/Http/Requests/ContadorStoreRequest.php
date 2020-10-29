<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContadorStoreRequest extends FormRequest
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
            'ubicacion' => 'required',
            'fecha_ultima_lectura' => 'required|date',
            'ultima_lectura' => 'required|integer',
            'ultimo_mes' => 'required|integer',
            'ultimo_anio' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return [
            'fecha_ultima_lectura' => 'fecha de última lectura',
            'ultima_lectura' => 'última lectura',
            'ultimo_mes' => 'último mes',
            'ultimo_anio' => 'último anio',
        ];
    }
}
