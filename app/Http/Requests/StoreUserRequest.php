<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            //
            'nim' => 'required|string|unique:users,nim|max:255',
            'password' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'major' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
        ];
    }
}
