<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Tanda Tangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Current Signature -->
                    @if($user->tanda_tangan)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Tanda Tangan Saat Ini</h3>
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <img src="{{ Storage::url($user->tanda_tangan) }}" 
                                     alt="Tanda Tangan" 
                                     class="max-h-32 border rounded">
                                <div class="mt-2">
                                    <form method="POST" action="{{ route('signature.delete') }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                onclick="return confirm('Yakin ingin menghapus tanda tangan?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Upload File -->
                        <div class="border rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">📁 Upload File Tanda Tangan</h3>
                            <form method="POST" action="{{ route('signature.store') }}" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="signature" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih File Tanda Tangan
                                    </label>
                                    <input type="file" 
                                           id="signature" 
                                           name="signature" 
                                           accept="image/png,image/jpg,image/jpeg"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                           required>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Format: PNG, JPG, JPEG. Maksimal 1MB.
                                    </p>
                                    @error('signature')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Cap/Stempel (Khusus untuk KADIN) -->
                                @if(auth()->user()->role === 'kadin' || str_contains(strtolower(auth()->user()->jabatan), 'kepala dinas'))
                                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <label for="cap_stempel" class="block text-sm font-medium text-yellow-700 mb-2">
                                        <i class="fas fa-stamp mr-1"></i>
                                        Cap/Stempel Dinas (Khusus Kepala Dinas)
                                    </label>
                                    <input type="file"
                                           id="cap_stempel"
                                           name="cap_stempel"
                                           accept="image/png,image/jpg,image/jpeg"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                    <p class="mt-1 text-sm text-gray-500">
                                        Format: PNG, JPG, JPEG. Maksimal 1MB. Background transparan disarankan.
                                    </p>
                                    @error('cap_stempel')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <!-- Checkbox untuk menggunakan cap -->
                                    <div class="mt-3">
                                        <label class="flex items-center">
                                            <input type="checkbox"
                                                   name="gunakan_cap"
                                                   value="1"
                                                   {{ $user->gunakan_cap ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-yellow-700 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Gunakan cap/stempel pada surat resmi
                                            </span>
                                        </label>
                                        <p class="mt-1 text-xs text-yellow-600">
                                            Cap akan ditampilkan di atas tanda tangan pada surat resmi
                                        </p>
                                    </div>
                                </div>
                                @endif

                                <button type="submit"
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                    <i class="fas fa-upload mr-2"></i>
                                    Upload Tanda Tangan
                                    @if(auth()->user()->role === 'kadin' || str_contains(strtolower(auth()->user()->jabatan), 'kepala dinas'))
                                        & Cap Stempel
                                    @endif
                                </button>
                            </form>
                        </div>

                        <!-- Draw Signature -->
                        <div class="border rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">✏️ Gambar Tanda Tangan</h3>
                            
                            <div class="mb-4">
                                <canvas id="signatureCanvas" 
                                        width="400" 
                                        height="200" 
                                        class="border border-gray-300 rounded cursor-crosshair"
                                        style="touch-action: none;">
                                </canvas>
                            </div>

                            <div class="flex space-x-2">
                                <button onclick="clearCanvas()" 
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Clear
                                </button>
                                <button onclick="saveSignature()" 
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan Tanda Tangan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-2">💡 Tips Tanda Tangan Digital:</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Gunakan background transparan atau putih</li>
                            <li>• Pastikan tanda tangan jelas dan tidak terlalu kecil</li>
                            <li>• Untuk hasil terbaik, scan tanda tangan asli dengan resolusi tinggi</li>
                            <li>• Tanda tangan akan muncul di PDF surat cuti yang disetujui</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Canvas Drawing
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        // Set canvas background to white
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Drawing settings
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';

        // Mouse events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Touch events for mobile
        canvas.addEventListener('touchstart', handleTouch);
        canvas.addEventListener('touchmove', handleTouch);
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            ctx.beginPath();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            ctx.stroke();
        }

        function stopDrawing() {
            isDrawing = false;
        }

        function handleTouch(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                            e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }

        function clearCanvas() {
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        }

        function saveSignature() {
            const dataURL = canvas.toDataURL('image/png');
            
            fetch('{{ route("signature.canvas") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    signature_data: dataURL
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tanda tangan berhasil disimpan!');
                    location.reload();
                } else {
                    alert('Gagal menyimpan tanda tangan!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan!');
            });
        }
    </script>
</x-app-layout>
