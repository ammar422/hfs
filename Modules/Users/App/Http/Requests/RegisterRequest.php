<?php

namespace Modules\Users\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name'        => 'required_if:user_type,dooner|string',
            'last_name'         => 'required_if:user_type,dooner|string',
            'email'             => 'required|email|unique:users,email',
            'mobile'            => 'required|string|unique:users,mobile',
            'sponsor_id'        => 'required|exists:users,id_code',

            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(6)
                    ->numbers(),
            ],
            'password_confirmation' => 'required|same:password',
        ];
    }


    /**
     * @param Validator $validator
     * 
     * @return object
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = lynx()->message(__('users::auth.validation_message'))
            ->data([
                'errors' => $errors,
                'status' => false,
            ])
            ->status(422)
            ->response();

        throw new HttpResponseException($response);
    }
}
