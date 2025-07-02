<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestCotizacion extends FormRequest
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

            'cliente' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'destino' => 'required|string|max:255',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'utilidad' => 'nullable|numeric',
            'envio' => 'nullable|numeric',
            'encomienda' => 'nullable|numeric',
            'facturacion' => 'nullable|numeric',
            'favor' => 'nullable|numeric',
            'pendiente' => 'nullable|numeric',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|numeric',
            'productos.*.cantidad' => 'required|numeric|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.registrado' => 'required|boolean',

        ];
    }
    public function messages(): array
    {
        return [
            'cliente.required' => 'El campo cliente es obligatorio.',
            'cliente.string' => 'El cliente debe ser una cadena de texto.',
            'cliente.max' => 'El cliente no debe superar los 255 caracteres.',

            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.string' => 'El código debe ser una cadena de texto.',
            'codigo.max' => 'El código no debe superar los 50 caracteres.',

            'destino.required' => 'El campo destino es obligatorio.',
            'destino.string' => 'El destino debe ser una cadena de texto.',
            'destino.max' => 'El destino no debe superar los 255 caracteres.',

            'subtotal.required' => 'El subtotal es obligatorio.',
            'subtotal.numeric' => 'El subtotal debe ser un número.',

            'total.required' => 'El total es obligatorio.',
            'total.numeric' => 'El total debe ser un número.',

            'utilidad.numeric' => 'La utilidad debe ser un número.',
            'envio.numeric' => 'El envío debe ser un número.',
            'encomienda.numeric' => 'La encomienda debe ser un número.',
            'facturacion.numeric' => 'La facturación debe ser un número.',
            'favor.numeric' => 'El favor debe ser un número.',
            'pendiente.numeric' => 'El pendiente debe ser un número.',

            'productos.required' => 'Debe ingresar al menos un producto.',
            'productos.array' => 'El campo productos debe ser un arreglo.',
            'productos.min' => 'Debe ingresar al menos un producto.',

            'productos.*.id.required' => 'El ID del producto es obligatorio.',
            'productos.*.id.numeric' => 'El ID del producto debe ser un número.',

            'productos.*.cantidad.required' => 'La cantidad del producto es obligatoria.',
            'productos.*.cantidad.numeric' => 'La cantidad del producto debe ser un número.',
            'productos.*.cantidad.min' => 'La cantidad del producto debe ser al menos 1.',

            'productos.*.precio.required' => 'El precio del producto es obligatorio.',
            'productos.*.precio.numeric' => 'El precio del producto debe ser un número.',
            'productos.*.precio.min' => 'El precio del producto no puede ser negativo.',

            'productos.*.registrado.required' => 'Debe indicar si el producto está registrado.',
            'productos.*.registrado.boolean' => 'El valor de registrado debe ser verdadero o falso.',
        ];
    }
}
