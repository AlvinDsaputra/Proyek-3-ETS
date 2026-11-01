<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Pelaporan Keamanan Makanan MBG') }}
            </h2>
            @if(auth()->user()->role === 'pelapor')
                <a href="{{ route('reports.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Buat Laporan Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search & Filter Bar -->
                <form method="GET" action="{{ route('reports.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laporan atau sekolah..." class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <select name="school_id" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Semua Sekolah --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                            <option value="ditinjau" {{ request('status') == 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                            <option value="diteruskan" {{ request('status') == 'diteruskan' ? 'selected' : '' }}>Diteruskan</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Filter</button>
                        <a href="{{ route('reports.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Reset</a>
                    </div>
                </form>

                <!-- Tabel Laporan -->
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="p-3 border">Tanggal Kejadian</th>
                                <th class="p-3 border">Sekolah</th>
                                <th class="p-3 border">Deskripsi Ringkas</th>
                                <th class="p-3 border">Jumlah Terdampak</th>
                                <th class="p-3 border">Status</th>
                                <th class="p-3 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="p-3 border text-sm">{{ $report->tanggal_kejadian }}</td>
                                    <td class="p-3 border text-sm font-semibold">{{ $report->school->nama_sekolah }}</td>
                                    <td class="p-3 border text-sm">{{ Str::limit($report->deskripsi_kejadian, 50) }}</td>
                                    <td class="p-3 border text-sm text-center">{{ $report->jumlah_terdampak }} siswa</td>
                                    <td class="p-3 border text-sm">
                                        <span class="px-2 py-1 text-xs font-bold rounded 
                                            @if($report->status === 'diajukan') bg-yellow-100 text-yellow-800
                                            @elseif($report->status === 'ditinjau') bg-blue-100 text-blue-800
                                            @elseif($report->status === 'diteruskan') bg-purple-100 text-purple-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td class="p-3 border text-sm">
                                        <a href="{{ route('reports.show', $report->id) }}" class="text-blue-600 hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">Tidak ada laporan yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>