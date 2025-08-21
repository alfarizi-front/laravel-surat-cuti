{{-- 
    Helper untuk format tanggal Indonesia
    Usage: @include('partials.tanggal-indonesia', ['date' => $tanggal, 'format' => 'full'])
    
    Format options:
    - 'full': 15 Januari 2024
    - 'short': 15 Jan 2024  
    - 'day': Senin, 15 Januari 2024
--}}

@php
    if (!$date) {
        echo '-';
        return;
    }
    
    // Pastikan $date adalah Carbon instance
    if (is_string($date)) {
        $date = \Carbon\Carbon::parse($date);
    }
    
    $bulanPendek = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];
    
    $bulanPanjang = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    $hari = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    
    $formatType = $format ?? 'full';
    
    switch ($formatType) {
        case 'short':
            echo $date->day . ' ' . $bulanPendek[$date->month] . ' ' . $date->year;
            break;
        case 'day':
            echo $hari[$date->format('l')] . ', ' . $date->day . ' ' . $bulanPanjang[$date->month] . ' ' . $date->year;
            break;
        case 'full':
        default:
            echo $date->day . ' ' . $bulanPanjang[$date->month] . ' ' . $date->year;
            break;
    }
@endphp
