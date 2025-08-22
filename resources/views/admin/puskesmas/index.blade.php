@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-clinic-medical mr-2"></i>
                        Kepala Puskesmas
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                                    <th width="25%">Puskesmas</th>
                                    <th width="20%">Kepala</th>
                                    <th width="15%">NIP</th>
                                    <th width="15%">Tanda Tangan</th>
                                    <th width="15%">Cap</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($puskesmas as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->kepala_puskesmas ?? '-' }}</td>
                                        <td>{{ $item->nip_kepala ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($item->tanda_tangan)
                                                <img src="{{ asset('storage/'.$item->tanda_tangan) }}" alt="ttd" style="max-height:40px;">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item->cap_stempel)
                                                <img src="{{ asset('storage/'.$item->cap_stempel) }}" alt="cap" style="max-height:40px;">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.puskesmas.edit', $item) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
