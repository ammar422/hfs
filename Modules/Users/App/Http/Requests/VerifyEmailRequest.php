<?php

namespace Modules\Users\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyEmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [
            'verification_code' => ['required', 'numeric'],
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
