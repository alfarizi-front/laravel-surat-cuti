{{-- 
    Helper untuk menampilkan tanda tangan
    Usage: @include('partials.signature-helper', ['user' => $user, 'isPreview' => false])
--}}

@php
    $hasSignature = $user && $user->tanda_tangan;
    $signaturePath = $hasSignature ? public_path('storage/' . $user->tanda_tangan) : null;
    $signatureExists = $signaturePath && file_exists($signaturePath);
@endphp

@if($hasSignature && $signatureExists)
    @if(isset($isPreview) && $isPreview)
        {{-- Untuk preview, gunakan asset URL --}}
        <img src="{{ asset('storage/' . $user->tanda_tangan) }}" 
             alt="Tanda Tangan {{ $user->nama }}" 
             class="signature-image"
             style="max-width: 150px; max-height: 70px; object-fit: contain;">
    @else
        {{-- Untuk PDF, gunakan base64 --}}
        @php
            try {
                $imageData = base64_encode(file_get_contents($signaturePath));
                $imageMime = mime_content_type($signaturePath);
                $base64Image = "data:{$imageMime};base64,{$imageData}";
            } catch (Exception $e) {
                $base64Image = null;
            }
        @endphp
        
        @if($base64Image)
            <img src="{{ $base64Image }}" 
                 alt="Tanda Tangan {{ $user->nama }}" 
                 class="signature-image"
                 style="max-width: 150px; max-height: 70px; object-fit: contain;">
        @else
            <div style="text-align: center; font-size: 10px; color: #666; padding: 20px 0;">
                (Gagal memuat tanda tangan)
            </div>
        @endif
    @endif
@else
    <div style="text-align: center; font-size: 10px; color: #666; padding: 20px 0;">
        @if($hasSignature && !$signatureExists)
            (File tanda tangan tidak ditemukan)
        @else
            (Belum upload tanda tangan)
        @endif
    </div>
@endif
