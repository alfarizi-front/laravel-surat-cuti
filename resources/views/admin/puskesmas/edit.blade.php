@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
 
                    <h3 class="card-title">Edit Kepala - {{ $puskesma->nama }}</h3>
 
                    <h3 class="card-title">Edit Kepala - {{ $puskesma->nama_puskesmas }}</h3>
 
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.puskesmas.update', $puskesma) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Nama Kepala Puskesmas</label>
                            <input type="text" name="kepala_puskesmas" class="form-control" value="{{ old('kepala_puskesmas', $puskesma->kepala_puskesmas) }}">
                        </div>
                        <div class="form-group">
                            <label>NIP Kepala</label>
                            <input type="text" name="nip_kepala" class="form-control" value="{{ old('nip_kepala', $puskesma->nip_kepala) }}">
                        </div>
                        <div class="form-group">
                            <label>Tanda Tangan</label>
                            @if($puskesma->tanda_tangan)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$puskesma->tanda_tangan) }}" alt="ttd" style="max-height:80px;">
                                </div>
                            @endif
                            <input type="file" name="tanda_tangan" class="form-control-file">
                        </div>
                        <div class="form-group">
                            <label>Cap / Stempel</label>
                            @if($puskesma->cap_stempel)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$puskesma->cap_stempel) }}" alt="cap" style="max-height:80px;">
                                </div>
                            @endif
                            <input type="file" name="cap_stempel" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.puskesmas.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
