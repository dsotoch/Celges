<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequest extends FormRequest
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

            'codigo'     => 'required|string|max:10|unique:personas,codigo',
            'nombres'    => 'required|string|max:255',
            'ruc'        => 'nullable|string|max:11|unique:personas,ruc',
            'direccion'  => 'nullable|string|max:255',
            'telefono'   => 'required|string|max:20|unique:personas,telefono',
            'email'      => 'nullable|email|max:255|unique:personas,email',
            'tipo_id'    => 'required|exists:tipos,id',

        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required'     => 'El código es obligatorio.',
            'codigo.max'          => 'El código no debe exceder los 10 caracteres.',
            'codigo.unique'       => 'El código ya está registrado.',

            'nombres.required'    => 'El nombre es obligatorio.',
            'nombres.string'      => 'El nombre debe ser una cadena de texto.',
            'nombres.max'         => 'El nombre no debe exceder los 255 caracteres.',

            'ruc.string'          => 'El RUC debe ser una cadena de texto.',
            'ruc.max'             => 'El RUC no debe exceder los 11 caracteres.',
            'ruc.unique'          => 'El RUC ya está registrado.',

            'direccion.string'    => 'La dirección debe ser una cadena de texto.',
            'direccion.max'       => 'La dirección no debe exceder los 255 caracteres.',

            'telefono.required'    => 'El telefono es obligatorio.',
            'telefono.max'        => 'El teléfono no debe exceder los 20 caracteres.',
            'telefono.unique'       => 'El telefono ya está registrado.',

            'email.email'         => 'El correo debe ser válido.',
            'email.max'           => 'El correo no debe exceder los 255 caracteres.',
            'email.unique'       => 'El email ya está registrado.',

            'tipo_id.required'    => 'Debe seleccionar un tipo.',
            'tipo_id.exists'      => 'El tipo seleccionado no es válido.',
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('show_modal', true);

        parent::failedValidation($validator);
    }
}
