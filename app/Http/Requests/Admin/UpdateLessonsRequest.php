<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
        'course_id' => ['nullable'],
        'title' => ['nullable'],
        'slug' => ['nullable'],
        'short_text' => ['nullable'],
        'full_text' => ['nullable'],
        'position' => ['nullable'],
        'free_lesson' => ['nullable'],
        'published' => ['nullable'],
        'content_id' => ['nullable'],
        'duration' => ['nullable'],
        'lesson_image' => ['nullable'],
        'media_type' => ['nullable'],
        'video' => ['nullable'],
        'video_file' => ['nullable'],
        'add_pdf' => ['nullable'],
        'add_audio' => ['nullable'],
        'downloadable_files' => ['nullable'],
    ];
}
    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'course_id.required' => 'Please select a course.',
            'course_id.exists' => 'The selected course is invalid.',
            'title.required' => 'Please enter a lesson title.',
            'title.max' => 'The lesson title cannot exceed 191 characters.',
            'slug.unique' => 'This slug is already in use. Please choose another.',
            'video.required_if' => 'Please enter a video URL for the selected media type.',
            'video.url' => 'Please enter a valid URL.',
            'video_file.required_if' => 'Please upload a video file.',
            'video_file.mimes' => 'The video must be in MP4, AVI, MOV, WEBM, or MKV format.',
            'video_file.max' => 'The video file must not exceed 500MB.',
            'add_pdf.mimes' => 'The file must be a PDF.',
            'add_pdf.max' => 'The PDF file must not exceed 50MB.',
            'add_audio.mimes' => 'The audio file must be in MP3, WAV, OGG, or M4A format.',
            'add_audio.max' => 'The audio file must not exceed 50MB.',
            'lesson_image.image' => 'The file must be an image.',
            'lesson_image.mimes' => 'The image must be in JPEG, PNG, JPG, GIF, or WEBP format.',
            'lesson_image.max' => 'The image must not exceed 2MB.',
            'downloadable_files.*.mimes' => 'Invalid file type. Allowed: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, ZIP, MP4, MP3, JPG, PNG.',
            'downloadable_files.*.max' => 'Each file must not exceed 50MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
 protected function prepareForValidation(): void
{
    $this->merge([
        'free_lesson' => $this->boolean('free_lesson'),
        'published' => $this->boolean('published'),
    ]);

    if (!$this->filled('slug') && $this->filled('title')) {
        $this->merge([
            'slug' => \Illuminate\Support\Str::slug($this->input('title')),
        ]);
    }

    $videos = (array) $this->input('video', []);
    $videos = array_filter($videos, fn($v) => trim($v) !== '');
    $this->merge(['video' => $videos]);
}
}
