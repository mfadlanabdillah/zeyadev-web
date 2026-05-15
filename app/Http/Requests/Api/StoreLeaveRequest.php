<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\LeaveRequest;
class StoreLeaveRequest extends FormRequest
{
   public function authorize(): bool
   {
    return true;
   }

   public function rules(): array
   {
        return [
            'type' => ['required', Rule::in([LeaveRequest::TYPE_ANNUAL, LeaveRequest::TYPE_SICK])],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'min:10', 'max:500'],
            'attachment' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:4000'],
        ];
    }

   public function messages(): array
   {
    return [
        'type.required' => 'Jenis cuti wajib diisi.',
        'start_date.after_or_equal' => 'Tanggal mulai cuti harus hari ini atau setelahnya.',
        'end_date.after_or_equal' => 'Tanggal akhir cuti harus sama dengan atau setelah tanggal mulai.',
        'reason.min' => 'Alasan cuti harus minimal 10 karakter.',
        'reason.max' => 'Alasan cuti tidak boleh lebih dari 500 karakter.',
        'attachment.mimes' => 'Lampiran harus berupa file PDF, JPG, JPEG, atau PNG.',
        'attachment.max' => 'Ukuran lampiran tidak boleh lebih dari 4MB.',
    ];
   }
}