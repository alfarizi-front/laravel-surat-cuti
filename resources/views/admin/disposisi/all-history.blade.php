@extends('layouts.app')

@section('title', 'Semua Riwayat Disposisi - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Semua Riwayat Disposisi</h4>
                    <div>
                        <a href="{{ route('admin.disposisi.pending-history') }}" class="btn btn-outline-warning">
                            <i class="fas fa-clock"></i> Pending Saja
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah Diproses</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">User Baru</label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="new_users_only" value="1" 
                                           {{ request('new_users_only') ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Hanya user baru (30 hari)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.disposisi.all-history') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if($disposisiHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Pengaju</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Jenis Cuti</th>
                                        <th>Disposisi Ke</th>
                                        <th>Status</th>
                                        <th>Tanggal Disposisi</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                           <th>Disposisi yang Diperlukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($disposisiHistory as $history)
                                        <tr>
                                            <td>{{ $history->pengaju->name }}</td>
                                            <td>{{ $history->pengaju->created_at->format('d M Y') }}</td>
                                            <td>{{ $history->jenis_cuti }}</td>
                                            <td>{{ $history->disposisi_ke }}</td>
                                            <td>{{ $history->status }}</td>
                                            <td>{{ $history->tanggal_disposisi ? $history->tanggal_disposisi->format('d M Y') : '-' }}</td>
                                            <td>{{ $history->catatan }}</td>
                                            <td>
                                                <a href="{{ route('admin.disposisi.detail', $history->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                               <td>
                                                   @php
                                                       // Example: required disposisi steps
                                                       $requiredSteps = ['KASI', 'KABID', 'KADIN'];
                                                       $userSteps = $history->pengaju->disposisiCuti->pluck('disposisi_ke')->toArray();
                                                       $missing = array_diff($requiredSteps, $userSteps);
                                                   @endphp
                                                   @if(count($missing) > 0)
                                                       <span class="text-danger">{{ implode(', ', $missing) }}</span>
                                                   @else
                                                       <span class="text-success">Lengkap</span>
                                                   @endif
                                               </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">Tidak ada riwayat disposisi yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection