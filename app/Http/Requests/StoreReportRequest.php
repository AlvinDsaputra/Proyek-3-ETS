<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MealDistribution;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya pengguna dengan role pelapor yang diizinkan membuat laporan
        return auth()->check() && auth()->user()->role === 'pelapor';
    }

    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'meal_distribution_id' => [
                'required',
                'exists:meal_distributions,id',
                // Distribusi makanan yang dipilih harus milik sekolah yang dipilih
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
            // Ketentuan file bukti (JPG, JPEG, PNG, PDF max 2048 KB / 2 MB)
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'school_id.required' => 'Sekolah wajib dipilih.',
            'meal_distribution_id.required' => 'Distribusi makanan wajib dipilih.',
            'tanggal_kejadian.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh melebihi hari ini.',
            'deskripsi_kejadian.required' => 'Deskripsi kejadian wajib diisi.',
            'deskripsi_kejadian.min' => 'Deskripsi kejadian minimal 10 karakter.',
            'jumlah_terdampak.required' => 'Jumlah korban/siswa terdampak wajib diisi.',
            'jumlah_terdampak.min' => 'Jumlah terdampak minimal 1 orang.',
            'bukti_file.mimes' => 'Bukti pendukung harus berformat JPG, JPEG, PNG, atau PDF.',
            'bukti_file.max' => 'Ukuran file bukti pendukung maksimal 2 MB.',
        ];
    }
}