<?php

namespace Modules\Users\App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [
            'email'                 => 'required|email|exists:users,email',
            'code'                  => 'required|numeric',
            'password'              => ['required', 'string', 'confirmed', Password::min(6)->numbers(),],
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
