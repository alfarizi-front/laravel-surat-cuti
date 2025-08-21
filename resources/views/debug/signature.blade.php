<!DOCTYPE html>
<html>
<head>
    <title>Debug Tanda Tangan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-box { border: 1px solid #ccc; padding: 15px; margin: 10px 0; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h1>Debug Tanda Tangan - User: {{ auth()->user()->nama }}</h1>
    
    <div class="debug-box">
        <h3>Informasi User</h3>
        <p><strong>Nama:</strong> {{ auth()->user()->nama }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Role:</strong> {{ auth()->user()->role }}</p>
        <p><strong>Tanda Tangan DB:</strong> 
            @if(auth()->user()->tanda_tangan)
                <span class="success">{{ auth()->user()->tanda_tangan }}</span>
            @else
                <span class="error">Tidak ada</span>
            @endif
        </p>
    </div>

    @if(auth()->user()->tanda_tangan)
        <div class="debug-box">
            <h3>Cek File Tanda Tangan</h3>
            @php
                $signaturePath = public_path('storage/' . auth()->user()->tanda_tangan);
                $signatureExists = file_exists($signaturePath);
                $storageUrl = asset('storage/' . auth()->user()->tanda_tangan);
            @endphp
            
            <p><strong>Path Database:</strong> {{ auth()->user()->tanda_tangan }}</p>
            <p><strong>Full Path:</strong> {{ $signaturePath }}</p>
            <p><strong>File Exists:</strong> 
                @if($signatureExists)
                    <span class="success">Ya</span>
                @else
                    <span class="error">Tidak</span>
                @endif
            </p>
            <p><strong>Storage URL:</strong> <a href="{{ $storageUrl }}" target="_blank">{{ $storageUrl }}</a></p>
            
            @if($signatureExists)
                @php
                    $fileSize = filesize($signaturePath);
                    $imageMime = mime_content_type($signaturePath);
                @endphp
                <p><strong>File Size:</strong> {{ number_format($fileSize / 1024, 2) }} KB</p>
                <p><strong>MIME Type:</strong> {{ $imageMime }}</p>
                
                <h4>Preview Gambar:</h4>
                <img src="{{ $storageUrl }}" alt="Tanda Tangan" style="max-width: 200px; border: 1px solid #ccc;">
                
                <h4>Base64 untuk PDF:</h4>
                @php
                    $imageData = base64_encode(file_get_contents($signaturePath));
                    $base64Image = 'data:' . $imageMime . ';base64,' . $imageData;
                @endphp
                <p><strong>Base64 Length:</strong> {{ strlen($base64Image) }} characters</p>
                <img src="{{ $base64Image }}" alt="Base64 Preview" style="max-width: 200px; border: 1px solid #ccc;">
            @endif
        </div>
    @endif

    <div class="debug-box">
        <h3>Storage Directory Check</h3>
        @php
            $storageSignaturePath = public_path('storage/signatures');
            $storageExists = is_dir($storageSignaturePath);
            $storageWritable = is_writable($storageSignaturePath);
        @endphp
        
        <p><strong>Storage Path:</strong> {{ $storageSignaturePath }}</p>
        <p><strong>Directory Exists:</strong> 
            @if($storageExists)
                <span class="success">Ya</span>
            @else
                <span class="error">Tidak</span>
            @endif
        </p>
        <p><strong>Directory Writable:</strong> 
            @if($storageWritable)
                <span class="success">Ya</span>
            @else
                <span class="error">Tidak</span>
            @endif
        </p>
        
        @if($storageExists)
            <h4>Files in signatures directory:</h4>
            @php
                $files = glob($storageSignaturePath . '/*');
            @endphp
            @if(count($files) > 0)
                <ul>
                    @foreach($files as $file)
                        <li>{{ basename($file) }} ({{ number_format(filesize($file) / 1024, 2) }} KB)</li>
                    @endforeach
                </ul>
            @else
                <p class="info">No files found</p>
            @endif
        @endif
    </div>

    <div class="debug-box">
        <h3>Actions</h3>
        <a href="{{ route('signature.show') }}" style="background: #007cba; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            Upload/Edit Tanda Tangan
        </a>
        <a href="{{ route('dashboard') }}" style="background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-left: 10px;">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
