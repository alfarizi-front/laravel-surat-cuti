@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Card Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Form Pengajuan Cuti</h2>
            </div>

            <form method="POST" action="{{ route('surat-cuti.store') }}" class="p-6">
                @csrf
                
                <!-- Data Pegawai Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-user-circle text-blue-600 text-xl mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Data Pegawai</h3>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" value="{{ auth()->user()->nama }}" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm bg-white" readonly>
                                </div>
                            </div>

                            <!-- NIP -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" value="{{ auth()->user()->nip }}" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm bg-white" readonly>
                                </div>
                            </div>

                            <!-- Jabatan -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input type="text" value="{{ auth()->user()->jabatan }}" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm bg-white" readonly>
                                </div>
                            </div>

                            <!-- Unit Kerja -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                    <input type="text" value="{{ auth()->user()->unit_kerja }}" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm bg-white" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Cuti Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Data Cuti</h3>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <!-- Jenis Cuti -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Cuti</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-list-alt text-gray-400"></i>
                                </div>
                                <select name="jenis_cuti_id" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih Jenis Cuti --</option>
                                    @foreach($jenisCuti as $jenis)
                                        <option value="{{ $jenis->id }}" {{ old('jenis_cuti_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('jenis_cuti_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Periode Cuti -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Tanggal Mulai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <input type="date" name="tanggal_mulai" 
                                           class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('tanggal_mulai') }}"
                                           min="{{ date('Y-m-d') }}">
                                </div>
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lama Hari -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lama Hari</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-clock text-gray-400"></i>
                                    </div>
                                    <input type="number" name="lama_hari" min="1" max="12"
                                           class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('lama_hari') }}"
                                           placeholder="Jumlah hari">
                                </div>
                                @error('lama_hari')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alasan Cuti -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Cuti</label>
                            <div class="relative">
                                <div class="absolute top-3 left-3">
                                    <i class="fas fa-comment-alt text-gray-400"></i>
                                </div>
                                <textarea name="alasan" rows="3" 
                                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Tuliskan alasan cuti Anda">{{ old('alasan') }}</textarea>
                            </div>
                            @error('alasan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Alamat Selama Cuti Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-map-marker-alt text-blue-600 text-xl mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Alamat Selama Cuti</h3>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <!-- Alamat -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                            <div class="relative">
                                <div class="absolute top-3 left-3">
                                    <i class="fas fa-home text-gray-400"></i>
                                </div>
                                <textarea name="alamat_cuti" rows="3" 
                                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Alamat lengkap selama cuti">{{ old('alamat_cuti') }}</textarea>
                            </div>
                            @error('alamat_cuti')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="text" name="telepon"
                                       class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('telepon') }}"
                                       placeholder="Nomor telepon yang bisa dihubungi">
                            </div>
                            @error('telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Ajukan Cuti
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom styles for readonly inputs */
    input[readonly] {
        background-color: #f8fafc;
        cursor: not-allowed;
    }
    
    /* Hover effect for interactive elements */
    .focus\:shadow-outline:focus {
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
    }
    
    /* Animation for submit button */
    button[type="submit"]:hover {
        transform: translateY(-1px);
        transition: all 0.2s;
    }
</style>
@endpush
