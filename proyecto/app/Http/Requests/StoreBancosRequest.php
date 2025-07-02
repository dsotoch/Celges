<?php

namespace App\Http\Requests;

use App\Enums\EnumTipoCuenta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreBancosRequest extends FormRequest
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
            'banco' => 'required|string|max:100',
            'tipo_cuenta' => ['required', new Enum(EnumTipoCuenta::class)],
            'numero_cuenta' => 'required|string|max:30|regex:/^[\d\-]+$/|unique:cuenta_bancarias,numero_cuenta',
            'titular' => 'required|string|max:150',
            'moneda' => 'required|in:PEN,USD',
        ];
    }
    public function messages(): array
    {
        return [
            'banco.required' => 'El campo Banco es obligatorio.',
            'banco.string' => 'El Banco debe ser un texto.',
            'banco.max' => 'El Banco no debe exceder los 100 caracteres.',

            'tipo_cuenta.required' => 'El campo Tipo de Cuenta es obligatorio.',
            'tipo_cuenta.string' => 'El Tipo de Cuenta debe ser un texto.',
            'tipo_cuenta.in' => 'El Tipo de Cuenta seleccionado no es válido.',

            'numero_cuenta.required' => 'El campo Número de Cuenta es obligatorio.',
            'numero_cuenta.string' => 'El Número de Cuenta debe ser un texto.',
            'numero_cuenta.max' => 'El Número de Cuenta no debe exceder los 30 caracteres.',
            'numero_cuenta.regex' => 'El Número de Cuenta solo debe contener dígitos y guiones.',
            'numero_cuenta.unique' => 'Ya existe una cuenta bancaria con ese número.',

            'titular.required' => 'El campo Titular es obligatorio.',
            'titular.string' => 'El Titular debe ser un texto.',
            'titular.max' => 'El nombre del Titular no debe exceder los 150 caracteres.',

            'moneda.required' => 'El campo Moneda es obligatorio.',
            'moneda.in' => 'La Moneda seleccionada no es válida. Solo se permite S/ o $.',


        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('show_modal', true);

        parent::failedValidation($validator);
    }
}
