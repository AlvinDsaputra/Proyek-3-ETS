<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\School;
use Illuminate\Http\Request;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        // Hanya Pemantau dan Koordinator yang diizinkan mengakses halaman rekap
        if (auth()->user()->role === 'pelapor') {
            abort(403, 'Akses ditolak. Halaman rekap hanya dapat diakses oleh Pemantau dan Koordinator Komunitas.');
        }

        $query = Report::query();

        // 1. Filter Berdasarkan Sekolah
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // 2. Tiga Informasi Ringkasan Sederhana
        $totalLaporan = (clone $query)->count();
        $laporanDiproses = (clone $query)->whereIn('status', ['diajukan', 'ditinjau'])->count();
        $laporanSelesai = (clone $query)->whereIn('status', ['diteruskan', 'selesai'])->count();

        // 3. Tabel Rekap per Sekolah
        $rekapSekolah = School::withCount([
            'reports as total_laporan',
            'reports as diajukan_count' => fn ($q) => $q->where('status', 'diajukan'),
            'reports as ditinjau_count' => fn ($q) => $q->where('status', 'ditinjau'),
            'reports as diteruskan_count' => fn ($q) => $q->where('status', 'diteruskan'),
            'reports as selesai_count' => fn ($q) => $q->where('status', 'selesai'),
        ])->get();

        $schools = School::all();

        return view('recap.index', compact(
            'totalLaporan',
            'laporanDiproses',
            'laporanSelesai',
            'rekapSekolah',
            'schools'
        ));
    }
}