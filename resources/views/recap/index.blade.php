<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Sederhana Laporan Keamanan Makanan MBG') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. Filter Sekolah -->
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('recap.index') }}" class="flex flex-col md:flex-row items-end gap-4">
                    <div class="w-full md:w-1/3">
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan Sekolah:</label>
                        <select name="school_id" id="school_id" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Semua Sekolah --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Terapkan Filter</button>
                        <a href="{{ route('recap.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Reset</a>
                    </div>
                </form>
            </div>

            <!-- 2. Tiga Informasi Ringkasan Sederhana -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-semibold text-gray-500">Total Laporan Kejadian</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $totalLaporan }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm font-semibold text-gray-500">Sedang Diproses (Diajukan / Ditinjau)</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $laporanDiproses }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-semibold text-gray-500">Telah Ditindaklanjuti / Selesai</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $laporanSelesai }}</div>
                </div>
            </div>

            <!-- 3. Tabel Rekapitulasi per Sekolah -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Rekapitulasi Laporan Per Sekolah</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">
                                <th class="p-3 border">Nama Sekolah</th>
                                <th class="p-3 border text-center">Diajukan</th>
                                <th class="p-3 border text-center">Ditinjau</th>
                                <th class="p-3 border text-center">Diteruskan</th>
                                <th class="p-3 border text-center">Selesai</th>
                                <th class="p-3 border text-center">Total Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapSekolah as $row)
                                <tr class="hover:bg-gray-50 border-b text-sm">
                                    <td class="p-3 border font-semibold">{{ $row->nama_sekolah }}</td>
                                    <td class="p-3 border text-center bg-yellow-50 font-semibold text-yellow-800">{{ $row->diajukan_count }}</td>
                                    <td class="p-3 border text-center bg-blue-50 font-semibold text-blue-800">{{ $row->ditinjau_count }}</td>
                                    <td class="p-3 border text-center bg-purple-50 font-semibold text-purple-800">{{ $row->diteruskan_count }}</td>
                                    <td class="p-3 border text-center bg-green-50 font-semibold text-green-800">{{ $row->selesai_count }}</td>
                                    <td class="p-3 border text-center font-bold text-gray-900">{{ $row->total_laporan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">Belum ada data sekolah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>