<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rows' => 'required|array',
            'rows.*.student_id' => 'required|exists:students,id',
            'rows.*.status' => 'required|in:hadir,izin,sakit,alfa',
            'rows.*.notes' => 'nullable|string|max:255',
        ];
    }
}
