<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsumoStoreRequest extends FormRequest
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
            'fecha_pago' => 'required',
            'contador_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'mes' => 'consumo'
        ];
    }

    public function messages()
    {
        return [
            'mes.unique' => "El :attribute correspondiente al mes :input-$this->anio ya se encuentra registrado en la base de datos"
        ];
    }
}
