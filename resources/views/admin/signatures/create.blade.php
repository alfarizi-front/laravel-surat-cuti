@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Signature Baru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.signatures.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.signatures.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('jabatan') is-invalid @enderror" 
                                           id="jabatan" 
                                           name="jabatan" 
                                           value="{{ old('jabatan') }}" 
                                           placeholder="Contoh: KADIN, Kepala Puskesmas, dll"
                                           required>
                                    @error('jabatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Jabatan harus unik dan sesuai dengan sistem disposisi
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" 
                                           name="nama" 
                                           value="{{ old('nama') }}" 
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
                                           value="{{ old('nip') }}" 
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
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Status Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Centang jika signature ini aktif digunakan
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="signature">Upload Tanda Tangan</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input @error('signature') is-invalid @enderror" 
                                               id="signature" 
                                               name="signature" 
                                               accept="image/jpeg,image/png,image/jpg">
                                        <label class="custom-file-label" for="signature">Pilih file tanda tangan...</label>
                                    </div>
                                    @error('signature')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Format: JPG, JPEG, PNG. Maksimal 2MB. Background transparan disarankan.
                                    </small>
                                    <div id="signaturePreview" class="mt-2" style="display: none;">
                                        <img id="signatureImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="stamp">Upload Cap/Stempel</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input @error('stamp') is-invalid @enderror" 
                                               id="stamp" 
                                               name="stamp" 
                                               accept="image/jpeg,image/png,image/jpg">
                                        <label class="custom-file-label" for="stamp">Pilih file cap/stempel...</label>
                                    </div>
                                    @error('stamp')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Format: JPG, JPEG, PNG. Maksimal 2MB. Background transparan disarankan.
                                    </small>
                                    <div id="stampPreview" class="mt-2" style="display: none;">
                                        <img id="stampImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" 
                                              name="keterangan" 
                                              rows="3" 
                                              placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Signature
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
    // File input labels
    document.querySelector('#signature').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Pilih file tanda tangan...';
        e.target.nextElementSibling.textContent = fileName;
        
        // Preview
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
        const fileName = e.target.files[0]?.name || 'Pilih file cap/stempel...';
        e.target.nextElementSibling.textContent = fileName;
        
        // Preview
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
