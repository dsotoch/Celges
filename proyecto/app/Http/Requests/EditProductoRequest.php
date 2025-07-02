<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditProductoRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'codigo' => [
                'required',
                'string',
                'max:50',
                Rule::unique("productos", "codigo")->ignore($id, 'id')
            ],

            'tipo'      => ['required', 'string', 'max:100'],
            'marca'     => ['required', 'string', 'max:100'],
            'modelo'    => ['required', 'string', 'max:100'],
            'capacidad' => ['required', 'string', 'max:100'],
        ];
    }
    public function messages()
    {
        return [
            'codigo.required'    => 'El campo código es obligatorio.',
            'codigo.string'      => 'El campo código debe ser una cadena de texto.',
            'codigo.max'         => 'El campo código no debe exceder los 50 caracteres.',
            'codigo.unique'      => 'El código ingresado ya está en uso.',

            'tipo.required'      => 'El campo tipo es obligatorio.',
            'tipo.string'        => 'El campo tipo debe ser una cadena de texto.',
            'tipo.max'           => 'El campo tipo no debe exceder los 100 caracteres.',


            'marca.string'       => 'El campo marca debe ser una cadena de texto.',
            'marca.max'          => 'El campo marca no debe exceder los 100 caracteres.',
            'marca.required'    => 'El campo marca es obligatorio.',

            'modelo.string'      => 'El campo modelo debe ser una cadena de texto.',
            'modelo.max'         => 'El campo modelo no debe exceder los 100 caracteres.',
            'modelo.required'    => 'El campo modelo es obligatorio.',

            'capacidad.string'   => 'El campo capacidad debe ser una cadena de texto.',
            'capacidad.max'      => 'El campo capacidad no debe exceder los 100 caracteres.',
            'capacidad.required'    => 'El campo capacidad es obligatorio.',

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        session()->flash("show_modal_edit", true);

        parent::failedValidation($validator);
    }
}
