<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Surat Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Progress Bar -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <!-- Step 1 -->
                                <div class="relative flex flex-col items-center">
                                    <div class="step-indicator w-10 h-10 rounded-full bg-white text-blue-600 flex items-center justify-center font-bold">1</div>
                                    <div class="mt-2 text-xs text-white">Data Pemohon</div>
                                </div>
                                <!-- Line 1 -->
                                <div class="step-line flex-1 h-1 bg-gray-200 mx-4"></div>
                                <!-- Step 2 -->
                                <div class="relative flex flex-col items-center">
                                    <div class="step-indicator w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center font-bold">2</div>
                                    <div class="mt-2 text-xs text-white">Data Cuti</div>
                                </div>
                                <!-- Line 2 -->
                                <div class="step-line flex-1 h-1 bg-gray-200 mx-4"></div>
                                <!-- Step 3 -->
                                <div class="relative flex flex-col items-center">
                                    <div class="step-indicator w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center font-bold">3</div>
                                    <div class="mt-2 text-xs text-white">Alamat Cuti</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 text-gray-900">
                    <!-- Info Cuti Tahunan -->
                    <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Informasi Cuti Tahunan {{ date('Y') }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-100">
                                <div class="text-sm text-blue-600 font-medium">Jatah Cuti</div>
                                <div class="text-2xl font-bold text-blue-800">{{ $cutiTahunan->jatah_cuti }} hari</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                                <div class="text-sm text-green-600 font-medium">Sisa Cuti {{ date('Y') }}</div>
                                <div class="text-2xl font-bold text-green-800">{{ $cutiTahunan->sisa_cuti }} hari</div>
                                <div class="text-xs text-green-600 mt-1">
                                    @if(isset($sisaCutiData) && isset($sisaCutiData[date('Y')]) && $sisaCutiData[date('Y')] !== null)
                                        (Sistem sisa cuti aktif)
                                    @else
                                        (Data default)
                                    @endif
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-yellow-100">
                                <div class="text-sm text-yellow-600 font-medium">Cuti Pending</div>
                                <div class="text-2xl font-bold text-yellow-800">{{ $cutiTahunan->cuti_pending }} hari</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-red-100">
                                <div class="text-sm text-red-600 font-medium">Cuti Digunakan</div>
                                <div class="text-2xl font-bold text-red-800">{{ $cutiTahunan->cuti_digunakan }} hari</div>
                            </div>
                        </div>

                        <!-- Riwayat Sisa Cuti -->
                        @if(isset($sisaCutiData) && !empty($sisaCutiData))
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="text-sm font-medium text-blue-800 mb-3">
                                <i class="fas fa-history mr-2"></i>
                                Riwayat Sisa Cuti
                            </h4>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach([2023, 2024, 2025] as $tahun)
                                    <div class="text-center">
                                        <div class="text-xs text-blue-600 font-medium">{{ $tahun }}</div>
                                        <div class="text-lg font-bold text-blue-800">
                                            {{ $sisaCutiData[$tahun] ?? 'N/A' }}
                                            @if($sisaCutiData[$tahun] !== null)
                                                hari
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-xs text-blue-600 mt-2">
                                * Data sisa cuti berdasarkan sistem database terbaru
                            </div>
                        </div>
                        @endif

                        <!-- Progress Bar -->
                        <div class="mt-4">
                            @php
                                $persentase = $cutiTahunan->getPersentasePenggunaan();
                                $colorClass = $persentase >= 100 ? 'bg-red-500' : ($persentase >= 80 ? 'bg-yellow-500' : 'bg-green-500');
                            @endphp
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                <span>Penggunaan Cuti</span>
                                <span>{{ number_format($persentase, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="{{ $colorClass }} h-3 rounded-full transition-all duration-300"
                                     style="width: {{ min($persentase, 100) }}%"></div>
                            </div>
                        </div>

                        @if($persentase >= 100)
                            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span class="font-medium">Peringatan:</span>
                                </div>
                                <p class="mt-1 text-sm">Anda telah menggunakan seluruh jatah cuti tahunan. Pengajuan baru akan memerlukan persetujuan khusus dari admin.</p>
                            </div>
                        @elseif($persentase >= 80)
                            <div class="mt-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span class="font-medium">Perhatian:</span>
                                </div>
                                <p class="mt-1 text-sm">Sisa cuti Anda tinggal sedikit. Pertimbangkan penggunaan cuti dengan bijak.</p>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('surat-cuti.store') }}" enctype="multipart/form-data" id="formCuti" class="space-y-8">
                        @csrf

                        <!-- Informasi Pegawai -->
                        <div class="bg-gray-50/50 p-6 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pegawai</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <div class="mt-1 p-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">
                                            {{ auth()->user()->nama }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">NIP</label>
                                        <div class="mt-1 p-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">
                                            {{ auth()->user()->nip }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Golongan/Ruang</label>
                                        <input type="text" name="golongan" value="{{ old('golongan', auth()->user()->golongan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="III/a">
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                        <div class="mt-1 p-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">
                                            {{ auth()->user()->jabatan }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                                    <div class="mt-1 p-2 bg-gray-50 border border-gray-200 rounded-md text-gray-700">
                                            {{ auth()->user()->unit_kerja }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Masa Kerja</label>
                                        <input type="text" name="masa_kerja" value="{{ old('masa_kerja', auth()->user()->masa_kerja) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="5 Tahun 0 Bulan">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Cuti -->
                        <div class="bg-blue-50/50 p-6 rounded-lg shadow-sm border border-gray-200 hover:bg-blue-50 transition-colors duration-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengajuan Cuti</h3>
                            
                            <!-- Jenis dan Alasan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="jenis_cuti_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti</label>
                                    <select id="jenis_cuti_id" name="jenis_cuti_id" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                                            required>
                                        <option value="">Pilih Jenis Cuti</option>
                                        @foreach($jenisCuti as $jenis)
                                            <option value="{{ $jenis->id }}" {{ old('jenis_cuti_id') == $jenis->id ? 'selected' : '' }}>
                                                {{ $jenis->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_cuti_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Periode Cuti -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                    <input type="date" id="tanggal_awal" name="tanggal_awal" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           value="{{ old('tanggal_awal') }}" required>
                                    @error('tanggal_awal')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                    <input type="date" id="tanggal_akhir" name="tanggal_akhir" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           value="{{ old('tanggal_akhir') }}" required>
                                    @error('tanggal_akhir')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="jumlah_hari" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Hari</label>
                                    <input type="number" id="jumlah_hari" name="jumlah_hari" min="1" max="90" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           value="{{ old('jumlah_hari') }}" readonly>
                                    <p class="mt-1 text-xs text-gray-500">Dihitung otomatis</p>
                                </div>
                            </div>

                            <!-- Alasan Cuti -->
                            <div class="mb-6">
                                <label for="alasan" class="block text-sm font-medium text-gray-700 mb-1">Alasan Cuti</label>
                                <textarea id="alasan" name="alasan" rows="3" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Jelaskan alasan pengajuan cuti Anda..." required>{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Kontak -->
                        <div class="bg-indigo-50/50 p-6 rounded-lg shadow-sm border border-gray-200 hover:bg-indigo-50 transition-colors duration-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak Selama Cuti</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="alamat_cuti" class="block text-sm font-medium text-gray-700 mb-1">Alamat Selama Cuti</label>
                                    <textarea id="alamat_cuti" name="alamat_cuti" rows="3" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Alamat lengkap selama cuti..." required>{{ old('alamat_cuti') }}</textarea>
                                    @error('alamat_cuti')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                    <input type="tel" id="telepon" name="telepon" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Nomor telepon yang dapat dihubungi" 
                                           pattern="[0-9]{10,13}"
                                           value="{{ old('telepon') }}" required>
                                    <p class="mt-1 text-xs text-gray-500">Format: 08xxxxxxxxxx</p>
                                    @error('telepon')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->jenis_pegawai === 'ASN')
                        <!-- Informasi Tambahan ASN -->
                        <div class="bg-purple-50/50 p-6 rounded-lg shadow-sm border border-gray-200 hover:bg-purple-50 transition-colors duration-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan ASN</h3>

                            <!-- Lampiran -->
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kontak_darurat" class="block text-sm font-medium text-gray-700 mb-1">Kontak Darurat</label>
                                    <input type="tel" id="kontak_darurat" name="kontak_darurat" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Nomor telepon darurat"
                                           pattern="[0-9]{10,13}"
                                           value="{{ old('kontak_darurat') }}">
                                    <p class="mt-1 text-xs text-gray-500">Nomor telepon keluarga/kerabat yang dapat dihubungi</p>
                                    @error('kontak_darurat')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="lampiran" class="block text-sm font-medium text-gray-700 mb-1">Lampiran Pendukung</label>
                                    <input type="file" id="lampiran" name="lampiran" 
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 
                                                  file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                                                  border border-gray-300 rounded-md"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, JPEG, PNG (Maks. 2MB)</p>
                                    @error('lampiran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Catatan untuk PPPK -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Informasi PPPK</h3>
                                    <p class="mt-2 text-sm text-yellow-700">
                                        Sebagai pegawai PPPK, Anda hanya dapat mengajukan Cuti Tahunan dengan form yang disederhanakan.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tombol Submit -->
                        <div class="flex items-center justify-end gap-4 mt-8">
                            <a href="{{ route('surat-cuti.index') }}" 
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Kembali
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Simpan Draft
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formCuti = document.getElementById('formCuti');
            if (!formCuti) return;

            const tanggalAwal = document.getElementById('tanggal_awal');
            const tanggalAkhir = document.getElementById('tanggal_akhir');
            const jumlahHari = document.getElementById('jumlah_hari');

            // Data cuti dari server
            const cutiData = {
                jatahCuti: {{ $cutiTahunan->jatah_cuti }},
                sisaCuti: {{ $cutiTahunan->sisa_cuti }},
                cutiPending: {{ $cutiTahunan->cuti_pending }},
                cutiDigunakan: {{ $cutiTahunan->cuti_digunakan }}
            };

            // Calculate working days between two dates
            function calculateWorkingDays(startDate, endDate) {
                let count = 0;
                const curDate = new Date(startDate);
                const lastDate = new Date(endDate);

                while (curDate <= lastDate) {
                    const dayOfWeek = curDate.getDay();
                    if (dayOfWeek !== 0 && dayOfWeek !== 6) { // Skip weekends
                        count++;
                    }
                    curDate.setDate(curDate.getDate() + 1);
                }
                return count;
            }

            // Update jumlah hari when dates change
            function updateJumlahHari() {
                if (tanggalAwal.value && tanggalAkhir.value) {
                    const start = new Date(tanggalAwal.value);
                    const end = new Date(tanggalAkhir.value);

                    if (start > end) {
                        tanggalAkhir.value = tanggalAwal.value;
                    }

                    const workingDays = calculateWorkingDays(start, end);
                    jumlahHari.value = workingDays;

                    // Update warning cuti
                    updateCutiWarning(workingDays);
                }
            }

            // Function untuk update warning cuti
            function updateCutiWarning(jumlahHariCuti) {
                const totalCutiIfApproved = cutiData.cutiDigunakan + cutiData.cutiPending + jumlahHariCuti;

                // Remove existing warning
                const existingWarning = document.getElementById('cuti-warning');
                if (existingWarning) {
                    existingWarning.remove();
                }

                if (jumlahHariCuti > 0) {
                    let warningHtml = '';
                    let warningClass = '';

                    if (totalCutiIfApproved > cutiData.jatahCuti) {
                        warningClass = 'bg-red-100 border-red-400 text-red-700';
                        warningHtml = `
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-medium">Peringatan: Melebihi Batas Maksimal!</span>
                            </div>
                            <p class="mt-1 text-sm">
                                Pengajuan ${jumlahHariCuti} hari akan membuat total cuti Anda menjadi ${totalCutiIfApproved} hari
                                (melebihi batas maksimal ${cutiData.jatahCuti} hari).
                                Pengajuan tetap bisa disubmit namun memerlukan persetujuan khusus dari admin.
                            </p>
                        `;
                    } else if (totalCutiIfApproved > cutiData.jatahCuti * 0.8) {
                        warningClass = 'bg-yellow-100 border-yellow-400 text-yellow-700';
                        warningHtml = `
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span class="font-medium">Perhatian: Mendekati Batas Maksimal</span>
                            </div>
                            <p class="mt-1 text-sm">
                                Pengajuan ${jumlahHariCuti} hari akan membuat total cuti Anda menjadi ${totalCutiIfApproved} hari.
                                Sisa cuti setelah pengajuan: ${cutiData.jatahCuti - totalCutiIfApproved} hari.
                            </p>
                        `;
                    } else {
                        warningClass = 'bg-green-100 border-green-400 text-green-700';
                        warningHtml = `
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span class="font-medium">Pengajuan Normal</span>
                            </div>
                            <p class="mt-1 text-sm">
                                Pengajuan ${jumlahHariCuti} hari masih dalam batas normal.
                                Sisa cuti setelah pengajuan: ${cutiData.jatahCuti - totalCutiIfApproved} hari.
                            </p>
                        `;
                    }

                    const warningDiv = document.createElement('div');
                    warningDiv.id = 'cuti-warning';
                    warningDiv.className = `mt-4 p-3 border rounded-lg ${warningClass}`;
                    warningDiv.innerHTML = warningHtml;

                    // Insert after form data cuti section
                    const dataCutiSection = document.querySelector('.bg-gray-50\\/50:nth-child(2)');
                    if (dataCutiSection) {
                        dataCutiSection.appendChild(warningDiv);
                    }
                }
            }

            // Date input event listeners
            tanggalAwal.addEventListener('change', function() {
                if (tanggalAkhir.value) {
                    const start = new Date(this.value);
                    const end = new Date(tanggalAkhir.value);
                    if (start > end) {
                        tanggalAkhir.value = this.value;
                    }
                }
                updateJumlahHari();
            });

            tanggalAkhir.addEventListener('change', updateJumlahHari);

            // Set minimum date for date inputs
            const today = new Date();
            const minDate = today.toISOString().split('T')[0];
            tanggalAwal.setAttribute('min', minDate);
            tanggalAkhir.setAttribute('min', minDate);

            // Phone number formatting
            const telepon = document.getElementById('telepon');
            const kontakDarurat = document.getElementById('kontak_darurat');

            function formatPhoneNumber(input) {
                if (!input) return;
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        if (!value.startsWith('0')) {
                            value = '0' + value;
                        }
                        if (value.length > 13) {
                            value = value.substring(0, 13);
                        }
                        e.target.value = value;
                    }
                });
            }

            formatPhoneNumber(telepon);
            formatPhoneNumber(kontakDarurat);

            // Form validation
            formCuti.addEventListener('submit', function(e) {
                const jenisCuti = document.getElementById('jenis_cuti_id');
                const alasan = document.getElementById('alasan');
                
                if (!jenisCuti.value) {
                    e.preventDefault();
                    alert('Silakan pilih jenis cuti');
                    jenisCuti.focus();
                    return;
                }

                if (!tanggalAwal.value || !tanggalAkhir.value) {
                    e.preventDefault();
                    alert('Silakan isi tanggal mulai dan selesai cuti');
                    return;
                }

                if (!alasan.value.trim()) {
                    e.preventDefault();
                    alert('Silakan isi alasan cuti');
                    alasan.focus();
                    return;
                }

                if (telepon && !telepon.value.match(/^0\d{9,12}$/)) {
                    e.preventDefault();
                    alert('Format nomor telepon tidak valid');
                    telepon.focus();
                    return;
                }
            });
        });
    </script>
    @endpush

</x-app-layout>
