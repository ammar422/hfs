<?php

namespace Modules\Users\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->guard('api')->check();
    }


    /**
     * Get the validation rules that apply to the request.
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = auth()->guard('api')->id();

        return [
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'mobile'        => 'required|string|unique:users,mobile,' . $userId,
            'photo'         => 'sometimes|nullable|image|mimetypes:image/*|max:2048',

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
