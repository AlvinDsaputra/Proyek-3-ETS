<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\School;
use App\Http\Requests\StoreReportRequest;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;

    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService =$reportService;
    }

    // Index dengan Ownership Check, Search, Filter, & Pagination
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Report::with(['school', 'distribution', 'user']);

        // Pelapor hanya melihat laporan miliknya sendiri 
        if (strtolower(trim($user->role)) === 'pelapor') {
            $query->where('user_id', $user->id);
        }

        // Filter Sekolah 
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Filter Status 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search Kata Kunci (Deskripsi Kejadian ATAU Nama Sekolah)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi_kejadian', 'like', '%' . $search . '%')
                ->orWhereHas('school', function ($sq) use ($search) {
                    $sq->where('nama_sekolah', 'like', '%' . $search . '%');
                });
            });
        }

        // Pagination 10 data & tetap mempertahankan parameter filter saat ganti halaman
        $reports = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $schools = \App\Models\School::all();

        return view('reports.index', compact('reports', 'schools'));
    }

    public function create()
    {
        $schools = School::all();
        return view('reports.create', compact('schools'));
    }

    public function store(StoreReportRequest $request)
    {
        $data =$request->validated();
        $data['user_id'] = auth()->id();$data['status'] = 'diajukan';

        // Upload File Bukti ke Laravel Storage 
        if ($request->hasFile('bukti_file')) {
            $path =$request->file('bukti_file')->store('evidences', 'public');
            $data['bukti_file'] =$path;
        }

        Report::create($data);

        return redirect()->route('reports.index')->with('success', 'Laporan kejadian berhasil dikirim.');
    }

    public function show(Report $report)
    {
        // Server side Ownership & Policy Authorization
        $this->authorize('view',$report);

        $report->load(['school', 'distribution', 'user', 'logs.user']);

        return view('reports.show', compact('report'));
    }

    // Action Peninjauan oleh Pemantau Komunitas
    public function review(Request $request, Report$report)
    {
        $this->authorize('review',$report);

        $request->validate([
            'catatan_pemantau' => 'required|string|min:5',
            'status' => 'required|in:diajukan,ditinjau',
        ]);

        // Validasi alur transisi status
        $this->reportService->validateTransition($report->status,$request->status);

        $report->update([
            'catatan_pemantau' => $request->catatan_pemantau,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Peninjauan laporan berhasil diperbarui.');
    }

    // Action Penerusan Informasi oleh Koordinator Komunitas
    public function forward(Request $request, Report$report)
    {
        $this->authorize('forward',$report);

        $request->validate([
            'diteruskan_ke' => 'required|string|min:3',
            'status' => 'required|in:diteruskan,selesai',
        ]);

        // Validasi alur transisi status
        $this->reportService->validateTransition($report->status,$request->status);

        $report->update([
            'diteruskan_ke' => $request->diteruskan_ke,
            'tanggal_diteruskan' => now(),
            'status' => $request->status,
        ]);

        return back()->with('success', 'Penerusan informasi berhasil dicatat.');
    }
}