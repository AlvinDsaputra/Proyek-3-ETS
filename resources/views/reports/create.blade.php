<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Kejadian MBG') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- 1. Dropdown Pilih Sekolah -->
                    <div class="mb-4">
                        <label for="school_id" class="block text-gray-700 font-bold mb-2">Sekolah</label>
                        <select name="school_id" id="school_id" class="w-full border-gray-300 rounded-md shadow-sm" required onchange="loadDistributions(this.value)">
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Dropdown Distribusi Makanan -->
                    <div class="mb-4">
                        <label for="meal_distribution_id" class="block text-gray-700 font-bold mb-2">Distribusi Makanan</label>
                        <select name="meal_distribution_id" id="meal_distribution_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Sekolah Terlebih Dahulu --</option>
                        </select>
                    </div>

                    <!-- 3. Tanggal Kejadian -->
                    <div class="mb-4">
                        <label for="tanggal_kejadian" class="block text-gray-700 font-bold mb-2">Tanggal Kejadian</label>
                        <input type="date" name="tanggal_kejadian" id="tanggal_kejadian" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('tanggal_kejadian') }}" required>
                    </div>

                    <!-- 4. Jumlah Terdampak -->
                    <div class="mb-4">
                        <label for="jumlah_terdampak" class="block text-gray-700 font-bold mb-2">Jumlah Siswa Terdampak</label>
                        <input type="number" name="jumlah_terdampak" id="jumlah_terdampak" min="1" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('jumlah_terdampak', 1) }}" required>
                    </div>

                    <!-- 5. Deskripsi Kejadian -->
                    <div class="mb-4">
                        <label for="deskripsi_kejadian" class="block text-gray-700 font-bold mb-2">Deskripsi Kejadian</label>
                        <textarea name="deskripsi_kejadian" id="deskripsi_kejadian" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('deskripsi_kejadian') }}</textarea>
                    </div>

                    <!-- 6. Upload Bukti Pendukung -->
                    <div class="mb-4">
                        <label for="bukti_file" class="block text-gray-700 font-bold mb-2">Bukti Pendukung (JPG, PNG, PDF max 2MB)</label>
                        <input type="file" name="bukti_file" id="bukti_file" class="w-full border-gray-300 rounded-md shadow-sm" accept=".jpg,.jpeg,.png,.pdf">
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function loadDistributions(schoolId) {
        const distributionSelect = document.getElementById('meal_distribution_id');
        distributionSelect.innerHTML = '<option value="">Memuat data distribusi...</option>';

        if (!schoolId) {
            distributionSelect.innerHTML = '<option value="">-- Pilih Sekolah Terlebih Dahulu --</option>';
            return;
        }

        fetch(`/api/schools/${schoolId}/distributions`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data distribusi');
                }
                return response.json();
            })
            .then(data => {
                distributionSelect.innerHTML = '<option value="">-- Pilih Distribusi Makanan --</option>';
                if (data.length === 0) {
                    distributionSelect.innerHTML = '<option value="">Tidak ada data distribusi untuk sekolah ini</option>';
                    return;
                }
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.tanggal_distribusi} | Menu: ${item.menu_makanan}`;
                    distributionSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Fetch error:', error);
                distributionSelect.innerHTML = '<option value="">Gagal memuat data distribusi</option>';
            });
    }
    </script>
</x-app-layout>