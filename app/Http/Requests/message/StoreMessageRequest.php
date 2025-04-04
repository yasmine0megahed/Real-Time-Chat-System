<?php

namespace App\Http\Requests\message;

use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class StoreMessageRequest extends FormRequest
{

    public function authorize(): bool
    {
        return JWTAuth::parseToken()->authenticate() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|min:1',
        ];
    }
}
