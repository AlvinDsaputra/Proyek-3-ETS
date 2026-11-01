<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan #') . $report->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Informational Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Informasi Kejadian</h3>
                    <span class="px-3 py-1 text-sm font-bold rounded 
                        @if($report->status === 'diajukan') bg-yellow-100 text-yellow-800
                        @elseif($report->status === 'ditinjau') bg-blue-100 text-blue-800
                        @elseif($report->status === 'diteruskan') bg-purple-100 text-purple-800
                        @else bg-green-100 text-green-800 @endif">
                        Status: {{ ucfirst($report->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><strong>Pelapor:</strong> {{ $report->user->name }}</div>
                    <div><strong>Sekolah:</strong> {{ $report->school->nama_sekolah }}</div>
                    <div><strong>Tanggal Kejadian:</strong> {{ $report->tanggal_kejadian }}</div>
                    <div><strong>Menu Makanan:</strong> {{ $report->distribution->menu_makanan }} ({{ $report->distribution->tanggal_distribusi }})</div>
                    <div><strong>Jumlah Terdampak:</strong> {{ $report->jumlah_terdampak }} siswa</div>
                    <div><strong>SPPG Penyedia:</strong> {{ $report->distribution->sppg_penyedia ?? '-' }}</div>
                </div>

                <div class="mt-4">
                    <strong>Deskripsi Kejadian:</strong>
                    <p class="mt-1 p-3 bg-gray-50 rounded border text-gray-700">{{ $report->deskripsi_kejadian }}</p>
                </div>

                <!-- File Bukti -->
                @if($report->bukti_file)
                    <div class="mt-4">
                        <strong>Bukti Pendukung:</strong>
                        <div class="mt-1">
                            <a href="{{ asset('storage/' . $report->bukti_file) }}" target="_blank" class="text-blue-600 underline">
                                Lihat / Unduh File Bukti
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Form Peninjauan untuk Pemantau -->
            @can('review', $report)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Aksi Pemantau Komunitas</h3>
                    <form action="{{ route('reports.review', $report->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Catatan Peninjauan</label>
                            <textarea name="catatan_pemantau" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('catatan_pemantau', $report->catatan_pemantau) }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Perbarui Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="diajukan" {{ $report->status === 'diajukan' ? 'selected' : '' }}>Tetap Diajukan</option>
                                <option value="ditinjau" {{ $report->status === 'ditinjau' ? 'selected' : '' }}>Tandai Ditinjau</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Peninjauan</button>
                    </form>
                </div>
            @endcan

            <!-- Form Penerusan untuk Koordinator -->
            @can('forward', $report)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Aksi Koordinator Komunitas</h3>
                    <form action="{{ route('reports.forward', $report->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Diteruskan Ke (Pihak Terkait: SPPG / Puskesmas / Dinas)</label>
                            <input type="text" name="diteruskan_ke" value="{{ old('diteruskan_ke', $report->diteruskan_ke) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Perbarui Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="diteruskan" {{ $report->status === 'diteruskan' ? 'selected' : '' }}>Tandai Diteruskan</option>
                                <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Tandai Selesai</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Catat Penerusan Informasi</button>
                    </form>
                </div>
            @endcan

            <!-- Histori Log Observer Perubahan Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Histori Perubahan Status (Observer Log)</h3>
                <ul class="divide-y divide-gray-200">
                    @forelse($report->logs as $log)
                        <li class="py-3 text-sm">
                            Status berubah dari <span class="font-bold text-gray-700">{{ $log->status_lama }}</span> menjadi <span class="font-bold text-blue-600">{{ $log->status_baru }}</span>
                            oleh <span class="font-semibold">{{ $log->user->name ?? 'Sistem' }}</span> pada <span class="text-gray-500">{{ $log->created_at->format('d M Y, H:i') }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-sm text-gray-500">Belum ada catatan perubahan status.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>