<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteStoreRequest extends FormRequest
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
            'nit' => 'nullable',
            'cui' => 'required|min:13|max:13|unique:clientes,cui',
            'nombres' => 'required',
            'apellidos' => 'required',
            'sexo' => 'required|in:F,M',
            'estado_civil' => 'required|in:S,C,U,V',
            'celular' => 'required|min:8|max:8',
            'correo' => 'nullable|email'
        ];
    }

    public function attributes()
    {
        return [
            'nit' => 'número de identificación tributaria (nit)',
            'cui' => 'código único de identificación (cui)',
            'estado_civil' => 'estado civil'
        ];
    }
}
