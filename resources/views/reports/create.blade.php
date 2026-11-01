<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Kejadian MBG') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <strong class="font-bold">Gagal Menyimpan Laporan:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- 1. Dropdown Pilih Sekolah -->
                    <div class="mb-4">
                        <label for="school_id" class="block text-gray-700 font-bold mb-2">Sekolah <span class="text-red-500">*</span></label>
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
                        <label for="meal_distribution_id" class="block text-gray-700 font-bold mb-2">Distribusi Makanan <span class="text-red-500">*</span></label>
                        <select name="meal_distribution_id" id="meal_distribution_id" class="w-full border-gray-300 rounded-md shadow-sm" required onchange="syncDistributionId(this.value)">
                            <option value="">-- Pilih Sekolah Terlebih Dahulu --</option>
                        </select>
                        <input type="hidden" name="distribution_id" id="distribution_id" value="{{ old('distribution_id', old('meal_distribution_id')) }}">
                    </div>

                    <!-- 3. Tanggal Kejadian -->
                    <div class="mb-4">
                        <label for="tanggal_kejadian" class="block text-gray-700 font-bold mb-2">Tanggal Kejadian <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kejadian" id="tanggal_kejadian" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('tanggal_kejadian') }}" required>
                    </div>

                    <!-- 4. Jumlah Terdampak -->
                    <div class="mb-4">
                        <label for="jumlah_terdampak" class="block text-gray-700 font-bold mb-2">Jumlah Siswa Terdampak <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_terdampak" id="jumlah_terdampak" min="1" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('jumlah_terdampak', 1) }}" required>
                    </div>

                    <!-- 5. Deskripsi Kejadian -->
                    <div class="mb-4">
                        <label for="deskripsi_kejadian" class="block text-gray-700 font-bold mb-2">Deskripsi Kejadian <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi_kejadian" id="deskripsi_kejadian" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('deskripsi_kejadian') }}</textarea>
                    </div>

                    <!-- 6. Upload Bukti Pendukung -->
                    <div class="mb-6">
                        <label for="bukti_file" class="block text-gray-700 font-bold mb-2">Bukti Pendukung (JPG, PNG, PDF max 2MB)</label>
                        <input type="file" name="bukti_file" id="bukti_file" class="w-full border-gray-300 rounded-md shadow-sm" accept=".jpg,.jpeg,.png,.pdf">
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" 
                                style="background-color: #2563eb; color: #ffffff; padding: 10px 20px; border-radius: 6px; font-weight: bold; border: none; cursor: pointer;">
                            Kirim Laporan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
    const selectedOldDistributionId = "{{ old('meal_distribution_id', old('distribution_id')) }}";

    function syncDistributionId(val) {
        const hiddenInput = document.getElementById('distribution_id');
        if (hiddenInput) hiddenInput.value = val;
    }

    function loadDistributions(schoolId, selectedId = null) {
        const distributionSelect = document.getElementById('meal_distribution_id');
        const hiddenInput = document.getElementById('distribution_id');
        distributionSelect.innerHTML = '<option value="">Memuat data distribusi...</option>';

        if (!schoolId) {
            distributionSelect.innerHTML = '<option value="">-- Pilih Sekolah Terlebih Dahulu --</option>';
            if (hiddenInput) hiddenInput.value = '';
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
                    if (hiddenInput) hiddenInput.value = '';
                    return;
                }
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.tanggal_distribusi} | Menu: ${item.menu_makanan}`;
                    
                    if (selectedId && item.id == selectedId) {
                        option.selected = true;
                        if (hiddenInput) hiddenInput.value = item.id;
                    }
                    
                    distributionSelect.appendChild(option);
                });

                if (distributionSelect.value) {
                    if (hiddenInput) hiddenInput.value = distributionSelect.value;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                distributionSelect.innerHTML = '<option value="">Gagal memuat data distribusi</option>';
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const schoolSelect = document.getElementById('school_id');
        if (schoolSelect && schoolSelect.value) {
            loadDistributions(schoolSelect.value, selectedOldDistributionId);
        }
    });
    </script>
</x-app-layout>