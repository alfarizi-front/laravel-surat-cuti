@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-signature mr-2"></i>
                        Manajemen Tanda Tangan & Cap
                    </h3>
                    <a href="{{ route('admin.signatures.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Signature
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Jabatan</th>
                                    <th width="20%">Nama</th>
                                    <th width="15%">NIP</th>
                                    <th width="10%">Tanda Tangan</th>
                                    <th width="10%">Cap</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($signatures as $signature)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $signature->jabatan }}</strong>
                                        @if($signature->keterangan)
                                            <br><small class="text-muted">{{ $signature->keterangan }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $signature->nama }}</td>
                                    <td>{{ $signature->nip ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($signature->signature_path)
                                            <img src="{{ $signature->getSignatureUrl() }}" 
                                                 alt="Signature" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 50px; max-width: 80px;"
                                                 data-toggle="modal" 
                                                 data-target="#signatureModal{{ $signature->id }}"
                                                 style="cursor: pointer;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($signature->stamp_path)
                                            <img src="{{ $signature->getStampUrl() }}" 
                                                 alt="Stamp" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 50px; max-width: 80px;"
                                                 data-toggle="modal" 
                                                 data-target="#stampModal{{ $signature->id }}"
                                                 style="cursor: pointer;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($signature->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.signatures.edit', $signature) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.signatures.destroy', $signature) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus signature ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Signature Modal -->
                                @if($signature->signature_path)
                                <div class="modal fade" id="signatureModal{{ $signature->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tanda Tangan - {{ $signature->nama }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ $signature->getSignatureUrl() }}" 
                                                     alt="Signature" 
                                                     class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Stamp Modal -->
                                @if($signature->stamp_path)
                                <div class="modal fade" id="stampModal{{ $signature->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cap - {{ $signature->nama }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ $signature->getStampUrl() }}" 
                                                     alt="Stamp" 
                                                     class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-signature fa-3x mb-3"></i>
                                        <br>
                                        Belum ada signature yang ditambahkan
                                        <br>
                                        <a href="{{ route('admin.signatures.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus mr-1"></i>
                                            Tambah Signature Pertama
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.img-thumbnail {
    cursor: pointer;
    transition: transform 0.2s;
}
.img-thumbnail:hover {
    transform: scale(1.1);
}
</style>
@endsection
