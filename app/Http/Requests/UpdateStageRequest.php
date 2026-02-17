<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStageRequest extends FormRequest
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
            'success_message' => ['nullable', 'string'],
            'fail_message' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'url'],
            'pengumuman_on' => ['required', 'boolean'],
            'isi_jadwal_on' => ['required', 'boolean'],
            'puzzles_on' => ['required', 'boolean'],
        ];
    }
}
