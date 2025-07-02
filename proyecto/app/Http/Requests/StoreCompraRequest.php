<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
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
            'numero' => 'required|unique:compras,numero',
            'persona_id' => 'required|exists:personas,id',
            'tipo_compra' => 'required|string',
            'fecha_compra' => 'required|date',
            'numero_documento' => 'required|unique:compras,numero_documento',
            'tipo_documento' => 'required|string',
            'total' => 'required|numeric|min:0',
            'estado' => 'required|in:pendiente,pagado',
        ];
    }
    public function messages()
    {
        return [
            'numero.required' => 'El número de la compra es obligatorio.',
            'numero.unique' => 'Este número ya ha sido registrado.',

            'persona_id.required' => 'Debe seleccionar una persona.',
            'persona_id.exists' => 'La persona seleccionada no es válida.',

            'tipo_compra.required' => 'El tipo de compra es obligatorio.',
            'tipo_compra.string' => 'El tipo de compra debe ser texto.',

            'fecha_compra.required' => 'Debe ingresar la fecha de compra.',
            'fecha_compra.date' => 'La fecha de compra no es válida.',

            'numero_documento.required' => 'Debe ingresar el número de documento.',
            'numero_documento.unique' => 'Este número de documento ya está registrado.',

            'tipo_documento.required' => 'Debe seleccionar el tipo de documento.',
            'tipo_documento.string' => 'El tipo de documento debe ser texto.',

            'total.required' => 'Debe ingresar el total de la compra.',
            'total.numeric' => 'El total debe ser un número.',
            'total.min' => 'El total no puede ser negativo.',

            'estado.required' => 'Debe indicar el estado de la compra.',
            'estado.in' => 'El estado debe ser PENDIENTE o PAGADO.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        session()->flash("show_modal",true);
        parent::failedValidation($validator);
    }
}
