@extends('layouts.app')

@section('title', 'Form Surat Cuti Resmi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-900">Form Surat Permintaan dan Pemberian Cuti</h1>
            <p class="mt-1 text-sm text-gray-600">
                Isi data-data berikut untuk menggenerate surat cuti resmi sesuai format pemerintahan Indonesia
            </p>
        </div>

        <form action="{{ route('pegawai.surat-cuti-resmi', $pegawai) }}" method="GET" class="px-6 py-4 space-y-6">
            <!-- Data Pegawai -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">I. Data Pegawai</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" value="{{ $pegawai->nama }}" readonly 
                               class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIP</label>
                        <input type="text" value="{{ $pegawai->nip }}" readonly
                               class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                        <input type="text" value="{{ $pegawai->jabatan }}" readonly
                               class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                        <input type="text" value="{{ $pegawai->unit_kerja }}" readonly
                               class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Golongan/Ruang</label>
                        <input type="text" name="golongan" value="III/a" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Masa Kerja</label>
                        <input type="text" name="masa_kerja" placeholder="5 Tahun 3 Bulan"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Data Surat -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">II. Data Surat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                        <input type="text" name="nomor_surat" value="800.1.11.4/___/{{ date('Y') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tempat</label>
                        <input type="text" name="tempat" value="Banjarmasin"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Surat</label>
                        <input type="text" name="tanggal_surat" value="{{ now()->format('d F Y') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Jenis dan Detail Cuti -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">III. Jenis dan Detail Cuti</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
                        <select name="jenis_cuti" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="Cuti Tahunan">Cuti Tahunan</option>
                            <option value="Cuti Besar">Cuti Besar</option>
                            <option value="Cuti Sakit">Cuti Sakit</option>
                            <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                            <option value="Cuti Alasan Penting">Cuti Karena Alasan Penting</option>
                            <option value="Cuti di Luar Tanggungan Negara">Cuti di Luar Tanggungan Negara</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alasan Cuti</label>
                        <textarea name="alasan_cuti" rows="3" placeholder="Keperluan keluarga"
                                  class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lama Cuti (hari)</label>
                            <input type="number" name="lama_cuti" value="2" min="1"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="text" name="tanggal_mulai" value="{{ now()->addDays(7)->format('d F Y') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="text" name="tanggal_selesai" value="{{ now()->addDays(8)->format('d F Y') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat Selama Cuti -->
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">IV. Alamat Selama Cuti</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="alamat_cuti" rows="3" placeholder="Jl. Contoh No. 123, Banjarmasin"
                                  class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon</label>
                        <input type="text" name="telepon" placeholder="0511-123456"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Data Pejabat -->
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">V. Data Pejabat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Atasan Langsung</label>
                        <input type="text" name="atasan_langsung" placeholder="Dr. Nama Atasan, M.Kes"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIP Atasan</label>
                        <input type="text" name="nip_atasan" placeholder="196501011990031001"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pejabat Berwenang</label>
                        <input type="text" name="pejabat_berwenang" placeholder="Dr. Kepala Dinas, M.Kes"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIP Pejabat</label>
                        <input type="text" name="nip_pejabat" placeholder="196001011985031001"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Catatan Cuti -->
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">VI. Catatan Cuti (Opsional)</h3>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ date('Y') - 2 }} Sisa</label>
                        <input type="number" name="sisa_cuti_n_2" value="12" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ date('Y') - 2 }} Diambil</label>
                        <input type="number" name="cuti_diambil_n_2" value="0" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ date('Y') - 1 }} Sisa</label>
                        <input type="number" name="sisa_cuti_n_1" value="12" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ date('Y') - 1 }} Diambil</label>
                        <input type="number" name="cuti_diambil_n_1" value="2" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ date('Y') }} Sisa</label>
                        <input type="number" name="sisa_cuti_n" value="12" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ date('Y') }} Diambil</label>
                        <input type="number" name="cuti_diambil_n" value="0" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">VII. Status Persetujuan (Opsional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pertimbangan Atasan</label>
                        <select name="pertimbangan_atasan" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">-- Belum Diproses --</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="perubahan">Perubahan</option>
                            <option value="ditangguhkan">Ditangguhkan</option>
                            <option value="tidak_disetujui">Tidak Disetujui</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Keputusan Pejabat</label>
                        <select name="keputusan_pejabat" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">-- Belum Diproses --</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="perubahan">Perubahan</option>
                            <option value="ditangguhkan">Ditangguhkan</option>
                            <option value="tidak_disetujui">Tidak Disetujui</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('pegawai.pdf.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    Generate PDF Surat Cuti Resmi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
