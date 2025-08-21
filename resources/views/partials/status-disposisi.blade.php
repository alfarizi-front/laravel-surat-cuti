{{-- 
    Helper untuk menampilkan status disposisi
    Usage: @include('partials.status-disposisi', ['status' => $disposisi->status])
--}}

@php
    $statusClass = '';
    $statusText = '';
    $statusIcon = '';
    
    switch ($status) {
        case 'sudah':
        case 'disetujui':
            $statusClass = 'status-approved';
            $statusText = 'DISETUJUI';
            $statusIcon = '✓';
            break;
        case 'ditolak':
            $statusClass = 'status-rejected';
            $statusText = 'DITOLAK';
            $statusIcon = '✗';
            break;
        case 'pending':
        case 'proses':
        default:
            $statusClass = 'status-pending';
            $statusText = 'PENDING';
            $statusIcon = '⏳';
            break;
    }
@endphp

<span class="{{ $statusClass }}">
    @if(isset($showIcon) && $showIcon)
        {{ $statusIcon }}
    @endif
    {{ $statusText }}
</span>

<style>
    .status-approved {
        color: green;
        font-weight: bold;
        background-color: #e8f5e8;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 9px;
    }
    
    .status-pending {
        color: orange;
        font-weight: bold;
        background-color: #fff3cd;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 9px;
    }
    
    .status-rejected {
        color: red;
        font-weight: bold;
        background-color: #f8d7da;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 9px;
    }
</style>
