@extends('layouts.landing')

@section('title', 'Kalender Magang')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-md-12">
            <h1 class="display-4 fw-bold mb-3 text-center" style="color: #5a60ff;">
                <i class="fas fa-calendar-alt me-2"></i> Kalender Jadwal Magang
            </h1>
            <p class="lead text-center text-muted mb-4">
                Lihat jadwal magang yang sedang berlangsung dan kuota bidang yang tersedia
            </p>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-days me-2"></i> Jadwal Magang Aktif
                    </h5>
                </div>
                <div class="card-body">
                    <div id="calendar-container" class="calendar-wrapper">
                        <!-- Calendar will be rendered here by JavaScript -->
                    </div>
                    
                    <!-- Calendar Controls -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <button id="prev-month" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-chevron-left"></i> Bulan Sebelumnya
                        </button>
                        <span id="current-month" class="fw-bold" style="font-size: 1.1rem; color: #5a60ff;"></span>
                        <button id="next-month" class="btn btn-sm btn-outline-primary">
                            Bulan Berikutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Calendar Legend -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-circle" style="color: #ffc107;"></i> Ada peserta magang
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-circle" style="color: #e9ecef;"></i> Tidak ada peserta
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Panel -->
                    <div id="detail-panel" class="mt-4 pt-3 border-top" style="display: none;">
                        <h6 class="fw-bold mb-3">Detail Periode Terpilih</h6>
                        <div id="detail-content" class="small">
                            <!-- Detail will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quota Info Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Kuota Bidang
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($kuotaBidang && count($kuotaBidang) > 0)
                        <div class="quota-list">
                            @foreach($kuotaBidang as $kuota)
                                <div class="quota-item p-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1 fw-bold" style="color: #5a60ff;">
                                                {{ $kuota['nama_bidang'] }}
                                            </h6>
                                            <small class="text-muted">
                                                Terisi: <span class="fw-bold">{{ $kuota['terisi'] }}</span> / 
                                                Kuota: <span class="fw-bold">{{ $kuota['kuota'] }}</span>
                                            </small>
                                        </div>
                                        <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                            {{ $kuota['persentase'] }}%
                                        </span>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="progress" style="height: 8px; background: #e9ecef;">
                                        <div class="progress-bar" 
                                             style="width: {{ $kuota['persentase'] }}%; background: linear-gradient(90deg, #667eea, #764ba2);">
                                        </div>
                                    </div>

                                    <small class="text-muted d-block mt-2">
                                        Sisa kuota: <span class="fw-bold" style="color: #5a60ff;">{{ $kuota['sisa'] }}</span>
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            <p>Belum ada data kuota bidang</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mentor & Sekretariat Access -->
            @auth
                @if(in_array(Auth::user()->role, ['mentor', 'sekretariat']))
                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-body text-center">
                            <a href="{{ route('mentor.manage-quota') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                                <i class="fas fa-cog me-2"></i> Kelola Kuota
                            </a>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Info Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info border-0" style="background: rgba(102, 126, 234, 0.1); border-left: 4px solid #667eea;">
                <i class="fas fa-info-circle" style="color: #667eea;"></i>
                <strong style="color: #667eea;"> Informasi:</strong> Kalender menampilkan jadwal magang yang sedang berlangsung tanpa menampilkan identitas peserta. 
                Untuk mendapatkan informasi lengkap, silakan lakukan pengajuan magang.
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .calendar-wrapper {
        margin: 20px 0;
    }

    .calendar-table {
        width: 100%;
        border-collapse: collapse;
    }

    .calendar-table th {
        background: #f8f9fa;
        padding: 12px;
        text-align: center;
        font-weight: 600;
        color: #5a60ff;
        border-bottom: 2px solid #e9ecef;
    }

    .calendar-table td {
        width: 14.28%;
        height: 60px;
        padding: 8px;
        text-align: center;
        border: 1px solid #e9ecef;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        background: white;
    }

    .calendar-table td:hover {
        background: rgba(102, 126, 234, 0.05);
        border-color: #667eea;
    }

    .calendar-table td.empty {
        background: #f8f9fa;
        cursor: default;
    }

    .calendar-table td.empty:hover {
        background: #f8f9fa;
        border-color: #e9ecef;
    }

    .calendar-table td.other-month {
        color: #ccc;
        background: #fafafa;
    }

    .calendar-table td.has-interns {
        background: rgba(255, 193, 7, 0.1);
        font-weight: 600;
        color: #5a60ff;
    }

    .calendar-table td.has-interns::after {
        content: '';
        position: absolute;
        bottom: 5px;
        left: 50%;
        transform: translateX(-50%);
        width: 8px;
        height: 8px;
        background: #ffc107;
        border-radius: 50%;
        display: block;
    }

    .calendar-table td.selected {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: #667eea;
    }

    .calendar-table td.selected::after {
        background: white;
    }

    .calendar-table td.today {
        border: 2px solid #667eea;
    }

    .quota-item:last-child {
        border-bottom: none !important;
    }

    .quota-list {
        max-height: 600px;
        overflow-y: auto;
    }

    @media (max-width: 768px) {
        .calendar-table td {
            height: 50px;
            font-size: 0.9rem;
            padding: 5px;
        }

        .calendar-table th {
            padding: 8px;
            font-size: 0.9rem;
        }
    }
</style>

<!-- Calendar JavaScript -->
<script>
    let currentDate = new Date();
    const internshipData = @json($semuaMagang);

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Update month display
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        document.getElementById('current-month').textContent = `${monthNames[month]} ${year}`;

        // Get first day and number of days in month
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();

        let html = '<table class="calendar-table"><thead><tr>';
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        
        dayNames.forEach(day => {
            html += `<th>${day}</th>`;
        });
        html += '</tr></thead><tbody><tr>';

        // Previous month days
        for (let i = firstDay - 1; i >= 0; i--) {
            html += `<td class="empty other-month">${daysInPrevMonth - i}</td>`;
        }

        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateStr = date.toISOString().split('T')[0];
            const today = new Date().toISOString().split('T')[0];

            // Check if date has internships
            const hasInterns = internshipData.some(m => {
                const mulai = new Date(m.tgl_mulai).toISOString().split('T')[0];
                const selesai = new Date(m.tgl_selesai).toISOString().split('T')[0];
                return dateStr >= mulai && dateStr <= selesai;
            });

            let classes = hasInterns ? 'has-interns' : '';
            if (dateStr === today) classes += ' today';

            html += `<td class="${classes}" onclick="selectDate('${dateStr}')">${day}</td>`;

            if ((firstDay + day) % 7 === 0) {
                html += '</tr><tr>';
            }
        }

        // Next month days
        const totalCells = firstDay + daysInMonth;
        const remainingCells = 42 - totalCells;
        for (let i = 1; i <= remainingCells; i++) {
            html += `<td class="empty other-month">${i}</td>`;
        }

        html += '</tr></tbody></table>';
        document.getElementById('calendar-container').innerHTML = html;
    }

    function selectDate(dateStr) {
        // Remove previous selection
        document.querySelectorAll('.calendar-table td').forEach(cell => {
            cell.classList.remove('selected');
        });

        // Add selection to clicked cell
        event.target.classList.add('selected');

        // Show detail
        const date = new Date(dateStr);
        const detailInterns = internshipData.filter(m => {
            const mulai = new Date(m.tgl_mulai).toISOString().split('T')[0];
            const selesai = new Date(m.tgl_selesai).toISOString().split('T')[0];
            return dateStr >= mulai && dateStr <= selesai;
        });

        if (detailInterns.length > 0) {
            let detailHtml = `<p class="mb-3"><strong>Tanggal: ${date.toLocaleDateString('id-ID')}</strong></p>`;
            
            // Group by field
            const groupedByField = {};
            detailInterns.forEach(m => {
                if (!groupedByField[m.bidang]) {
                    groupedByField[m.bidang] = 0;
                }
                groupedByField[m.bidang]++;
            });

            detailHtml += '<div class="ms-2">';
            Object.entries(groupedByField).forEach(([bidang, count]) => {
                detailHtml += `<p class="mb-1">
                    <i class="fas fa-briefcase" style="color: #667eea;"></i>
                    <strong>${bidang}</strong>: <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2);">${count} peserta</span>
                </p>`;
            });
            detailHtml += '</div>';

            document.getElementById('detail-content').innerHTML = detailHtml;
            document.getElementById('detail-panel').style.display = 'block';
        } else {
            document.getElementById('detail-panel').style.display = 'none';
        }
    }

    // Navigation
    document.getElementById('prev-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // Initial render
    renderCalendar();
</script>
@endsection
