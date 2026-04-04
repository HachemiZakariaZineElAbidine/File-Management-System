<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files'   => 'required|array|max:5',
            'files.*' => 'required|file|max:20480|mimes:pdf,docx,png,jpg,jpeg,odt',
        ];
    }

    public function messages(): array
    {
        return [
            'files.max'    => 'You can upload a maximum of 5 files at once.',
            'files.*.mimes' => 'Only PDF, DOCX, PNG, JPG, and ODT files are allowed.',
            'files.*.max'  => 'Each file must not exceed 20MB.',
        ];
    }
}
