<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization will be handled in the controller
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Since we're uploading files individually now, we expect a single file
        return [
            'files' => [
                'required',
                'file',
                'max:10240', // 10MB max
                'mimes:jpeg,jpg,png,gif,bmp,svg,pdf,doc,docx,xls,xlsx,txt,csv,zip,rar',
                function ($attribute, $value, $fail) {
                    // Check for potential malicious files
                    if ($value instanceof \Illuminate\Http\UploadedFile) {
                        $forbidden = ['php', 'js', 'exe', 'sh', 'bat'];
                        $ext = strtolower($value->getClientOriginalExtension());
                        if (in_array($ext, $forbidden)) {
                            $fail('The file type ".' . $ext . '" is not allowed for security reasons.');
                        }
                    }
                },
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'files.required' => 'Please select at least one file to upload.',
            'files.array' => 'The files must be provided as an array.',
            'files.*.required' => 'Each file is required.',
            'files.*.file' => 'The uploaded file must be a valid file.',
            'files.*.max' => 'The file size cannot exceed 10MB.',
            'files.*.mimes' => 'The file must be one of the following types: JPEG, JPG, PNG, GIF, BMP, SVG, PDF, DOC, DOCX, XLS, XLSX, TXT, CSV, ZIP, RAR.'
        ];
    }
}
