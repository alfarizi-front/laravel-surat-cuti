@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Signature - {{ $signature->jabatan }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.signatures.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.signatures.update', $signature) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('jabatan') is-invalid @enderror" 
                                           id="jabatan" 
                                           name="jabatan" 
                                           value="{{ old('jabatan', $signature->jabatan) }}" 
                                           placeholder="Contoh: KADIN, Kepala Puskesmas, dll"
                                           required>
                                    @error('jabatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" 
                                           name="nama" 
                                           value="{{ old('nama', $signature->nama) }}" 
                                           placeholder="Nama lengkap pejabat"
                                           required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" 
                                           class="form-control @error('nip') is-invalid @enderror" 
                                           id="nip" 
                                           name="nip" 
                                           value="{{ old('nip', $signature->nip) }}" 
                                           placeholder="Nomor Induk Pegawai">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               {{ old('is_active', $signature->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Status Aktif
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" 
                                              name="keterangan" 
                                              rows="3" 
                                              placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $signature->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="signature">Upload Tanda Tangan Baru</label>
                                    @if($signature->signature_path)
                                        <div class="mb-2">
                                            <small class="text-muted">Tanda tangan saat ini:</small><br>
                                            <img src="{{ $signature->getSignatureUrl() }}" 
                                                 alt="Current Signature" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 80px;">
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input @error('signature') is-invalid @enderror" 
                                               id="signature" 
                                               name="signature" 
                                               accept="image/jpeg,image/png,image/jpg">
                                        <label class="custom-file-label" for="signature">Pilih file tanda tangan baru...</label>
                                    </div>
                                    @error('signature')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Kosongkan jika tidak ingin mengubah. Format: JPG, JPEG, PNG. Maksimal 2MB.
                                    </small>
                                    <div id="signaturePreview" class="mt-2" style="display: none;">
                                        <img id="signatureImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="stamp">Upload Cap/Stempel Baru</label>
                                    @if($signature->stamp_path)
                                        <div class="mb-2">
                                            <small class="text-muted">Cap saat ini:</small><br>
                                            <img src="{{ $signature->getStampUrl() }}" 
                                                 alt="Current Stamp" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 80px;">
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input @error('stamp') is-invalid @enderror" 
                                               id="stamp" 
                                               name="stamp" 
                                               accept="image/jpeg,image/png,image/jpg">
                                        <label class="custom-file-label" for="stamp">Pilih file cap/stempel baru...</label>
                                    </div>
                                    @error('stamp')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Kosongkan jika tidak ingin mengubah. Format: JPG, JPEG, PNG. Maksimal 2MB.
                                    </small>
                                    <div id="stampPreview" class="mt-2" style="display: none;">
                                        <img id="stampImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Update Signature
                        </button>
                        <a href="{{ route('admin.signatures.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File input labels and preview
    document.querySelector('#signature').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Pilih file tanda tangan baru...';
        e.target.nextElementSibling.textContent = fileName;
        
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('signatureImg').src = e.target.result;
                document.getElementById('signaturePreview').style.display = 'block';
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    document.querySelector('#stamp').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Pilih file cap/stempel baru...';
        e.target.nextElementSibling.textContent = fileName;
        
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('stampImg').src = e.target.result;
                document.getElementById('stampPreview').style.display = 'block';
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});
</script>
@endsection
