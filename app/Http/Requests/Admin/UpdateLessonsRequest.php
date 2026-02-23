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
        $lessonId = $this->route('lesson');
        
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:191'],
            'slug' => [
                'nullable', 
                'string', 
                'max:191', 
                Rule::unique('lessons', 'slug')->ignore($lessonId)
            ],
            'short_text' => ['nullable', 'string', 'max:500'],
            'full_text' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:0'],
            'free_lesson' => ['nullable', 'boolean'],
            'published' => ['nullable', 'boolean'],
            'content_id' => ['nullable', 'exists:course_contents,id'],
            'duration' => ['nullable', 'string', 'max:50'],
            'lesson_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            
            // Video media validation
            'media_type' => ['nullable', Rule::in(['youtube', 'vimeo', 'upload', 'embed', ''])],
            'video' => ['nullable', 'required_if:media_type,youtube,vimeo,embed', 'url'],
            'video_file' => [
                'nullable',
                'required_if:media_type,upload',
                'file',
                'mimes:mp4,avi,mov,webm,mkv',
                'max:512000' // 500MB
            ],
            
            // PDF validation
            'add_pdf' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:51200' // 50MB
            ],
            
            // Audio validation
            'add_audio' => [
                'nullable',
                'file',
                'mimes:mp3,wav,ogg,m4a',
                'max:51200' // 50MB
            ],
            
            // Downloadable files validation
            'downloadable_files' => ['nullable', 'array'],
            'downloadable_files.*' => [
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,mp4,mp3,jpg,jpeg,png',
                'max:51200' // 50MB per file
            ],
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
        // Convert checkbox values to boolean
        $this->merge([
            'free_lesson' => $this->boolean('free_lesson'),
            'published' => $this->boolean('published'),
        ]);
    }
}
