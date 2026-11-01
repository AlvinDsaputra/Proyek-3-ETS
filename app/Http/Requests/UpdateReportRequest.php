<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MealDistribution;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $report = $this->route('report');
        
        // Pelapor hanya boleh mengedit laporan miliknya sendiri dan jika status masih 'diajukan'
        return auth()->check() && 
               $report && 
               $report->user_id === auth()->id() && 
               $report->status === 'diajukan';
    }

    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'meal_distribution_id' => [
                'required',
                'exists:meal_distributions,id',
                function ($attribute, $value, $fail) {
                    $distribution = MealDistribution::find($value);
                    if ($distribution && $distribution->school_id != $this->school_id) {
                        $fail('Distribusi makanan yang dipilih tidak sesuai dengan sekolah yang dipilih.');
                    }
                },
            ],
            'tanggal_kejadian' => 'required|date|before_or_equal:today',
            'deskripsi_kejadian' => 'required|string|min:10',
            'jumlah_terdampak' => 'required|integer|min:1',
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}