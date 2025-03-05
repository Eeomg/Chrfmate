<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Users\User;
use App\Http\Requests\AbstractApiRequest;

class RegisterRequest extends AbstractApiRequest
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
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->where('verified',false)->exists()) {
                        $fail('Email exists but not verified.');
                    }
                },
        ],
            'password' => 'required|string|min:6',
        ];
    }
}
