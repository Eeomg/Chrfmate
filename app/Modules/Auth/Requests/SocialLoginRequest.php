<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Users\User;
use App\Http\Requests\AbstractApiRequest;

class SocialLoginRequest extends AbstractApiRequest
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
            'email' => 'required|email',
            'name' => 'required|string',
            'avatar' => 'nullable|string',
            'idToken' => 'required|string'
        ];
    }
}
