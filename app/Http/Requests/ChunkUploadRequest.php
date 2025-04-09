<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChunkUploadRequest extends FormRequest
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
        return [
            'chunk' => 'required|file',
            'chunk_index' => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1',
            'temp_filename' => 'required|string',
            'filename' => 'required|string',
            'file_size' => 'required|integer|min:1|max:20971520', // Max 20MB (increased from 10MB)
            'mime_type' => 'required|string',
            'ai_classify' => 'boolean'
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
            'chunk.required' => 'The chunk file is required.',
            'chunk.file' => 'The chunk must be a valid file.',
            'chunk_index.required' => 'The chunk index is required.',
            'chunk_index.integer' => 'The chunk index must be an integer.',
            'chunk_index.min' => 'The chunk index must be at least 0.',
            'total_chunks.required' => 'The total number of chunks is required.',
            'total_chunks.integer' => 'The total number of chunks must be an integer.',
            'total_chunks.min' => 'The total number of chunks must be at least 1.',
            'temp_filename.required' => 'The temporary filename is required.',
            'filename.required' => 'The original filename is required.',
            'file_size.required' => 'The file size is required.',
            'file_size.integer' => 'The file size must be an integer.',
            'file_size.min' => 'The file size must be at least 1 byte.',
            'file_size.max' => 'The file size cannot exceed 20MB.',
            'mime_type.required' => 'The MIME type is required.',
            'ai_classify.boolean' => 'The AI classify flag must be a boolean value.'
        ];
    }
}
