<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsumoUpdateRequest extends FormRequest
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
            'fecha_emision' => 'required',
            'mes' => 'required',
            'anio' => 'required',
            'fecha_ultima_lectura' => 'required',
            'ultima_lectura' => 'required',
            'fecha_lectura_actual' => 'required',
            'lectura_actual' => 'required',
            'foto_lectura_actual' => 'nullable|max:10000',
            'consumo' => 'required',
            'monto_consumo' => 'required',
            'exceso' => 'required',
            'monto_exceso' => 'required',
            'fecha_pago' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'mes' => 'cosumo'
        ];
    }

}
