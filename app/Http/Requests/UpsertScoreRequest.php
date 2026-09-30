<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertScoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'rows' => 'required|array',
            'rows.*.student_id' => 'required|exists:students,id',
            'rows.*.value' => 'required|numeric|min:0|max:100',
            'semester' => 'nullable|in:ganjil,genap',
            'academic_year' => 'nullable|string|max:20',
        ];
    }
}
