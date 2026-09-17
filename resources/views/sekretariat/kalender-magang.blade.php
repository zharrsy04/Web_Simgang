@extends('layouts.app')

@section('title', 'Kalender Magang')

@section('content')
<div class="container-fluid py-5">
    <div class="mb-4">
        <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <h2 class="dashboard-header">Kalender Magang</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Sidebar with quota information -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-bars me-2"></i> Kuota Bidang
                    </h5>
                </div>
                <div class="card-body p-0" id="quota-sidebar">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p class="mt-2 mb-0">Memuat data...</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Detail Periode
                    </h5>
                </div>
                <div class="card-body p-0" id="period-detail">
                    <div class="text-center py-5 text-muted p-3">
                        <p class="mb-0">Pilih tanggal pada kalender untuk melihat detail</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar Libraries -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize FullCalendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        locale: 'id',
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            list: 'Daftar'
        },
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        dayNamesShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        events: function(info, successCallback, failureCallback) {
            fetch('/api/internship-data')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Raw API Response:', data);
                    
                    if (!Array.isArray(data)) {
                        console.error('Data is not an array:', data);
                        successCallback([]);
                        return;
                    }
                    
                    const events = [];
                    data.forEach(item => {
                        if (item.tgl_mulai && item.tgl_selesai && item.bidang) {
                            // Tambahkan 1 hari ke tgl_selesai untuk inclusive end date
                            const endDate = new Date(item.tgl_selesai);
                            endDate.setDate(endDate.getDate() + 1);
                            
                            events.push({
                                title: `${item.siswa_count} Siswa`,
                                start: item.tgl_mulai,
                                end: endDate.toISOString().split('T')[0],
                                backgroundColor: '#f59e0b',
                                borderColor: '#f59e0b',
                                textColor: '#ffffff',
                                display: 'background',
                                extendedProps: {
                                    bidang: item.bidang,
                                    siswa_count: item.siswa_count,
                                    tgl_mulai: item.tgl_mulai,
                                    tgl_selesai: item.tgl_selesai
                                }
                            });
                        }
                    });
                    
                    console.log('Events created:', events);
                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            showPeriodDetail(info.event.extendedProps);
        },
        dateClick: function(info) {
            const clickedDate = info.dateStr;
            console.log('Date clicked:', clickedDate);
            
            // Fetch data for clicked date
            fetch('/api/internship-data')
                .then(response => response.json())
                .then(data => {
                    console.log('API data:', data);
                    
                    const matchingData = data.filter(item => {
                        return clickedDate >= item.tgl_mulai && clickedDate <= item.tgl_selesai;
                    });
                    
                    console.log('Matching data for', clickedDate, ':', matchingData);
                    
                    if (matchingData.length > 0) {
                        showPeriodDetail(matchingData[0]);
                    } else {
                        showNoPeriodDetail();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNoPeriodDetail();
                });
        }
    });
    calendar.render();

    // Load quota data
    loadQuotaData();
});

function loadQuotaData() {
    fetch('/api/internship-data')
        .then(response => response.json())
        .then(data => {
            // Get unique bidang
            const bidang = [...new Set(data.map(item => item.bidang))];
            
            let html = '<div class="list-group list-group-flush">';
            bidang.forEach(b => {
                const bidangData = data.filter(item => item.bidang === b);
                const totalSiswa = bidangData.reduce((sum, item) => sum + item.siswa_count, 0);
                
                html += `
                    <div class="list-group-item border-0 py-3 px-3" style="border-bottom: 1px solid #e9ecef;">
                        <h6 class="mb-2 fw-bold" style="color: #667eea;">${b}</h6>
                        <div class="small text-muted">
                            <p class="mb-1">Siswa Aktif: <strong>${totalSiswa}</strong></p>
                            <p class="mb-0">Periode: ${bidangData.map(item => `${new Date(item.tgl_mulai).toLocaleDateString('id-ID')} - ${new Date(item.tgl_selesai).toLocaleDateString('id-ID')}`).join(', ')}</p>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            document.getElementById('quota-sidebar').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading quota data:', error);
            document.getElementById('quota-sidebar').innerHTML = '<div class="text-center py-5 text-danger"><p>Gagal memuat data kuota</p></div>';
        });
}

function showPeriodDetail(data) {
    const html = `
        <div class="p-3">
            <p class="mb-2">
                <strong>Bidang:</strong><br>
                <span class="badge bg-info">${data.bidang}</span>
            </p>
            <p class="mb-2">
                <strong>Periode:</strong><br>
                ${new Date(data.tgl_mulai).toLocaleDateString('id-ID')} - ${new Date(data.tgl_selesai).toLocaleDateString('id-ID')}
            </p>
            <p class="mb-0">
                <strong>Siswa Aktif:</strong><br>
                <span class="badge bg-success">${data.siswa_count}</span>
            </p>
        </div>
    `;
    document.getElementById('period-detail').innerHTML = html;
}

function showNoPeriodDetail() {
    document.getElementById('period-detail').innerHTML = `
        <div class="text-center py-5 text-muted p-3">
            <p class="mb-0">Tidak ada magang pada tanggal ini</p>
        </div>
    `;
}
</script>

<style>
    .dashboard-header {
        font-size: 1.8rem;
        font-weight: 600;
        color: #f8f9fa;
        text-align: center;
        position: relative;
        margin-bottom: 2rem;
    }

    .dashboard-header:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 3px;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .fc {
        font-family: 'Poppins', sans-serif;
    }

    .fc-button-primary {
        background-color: #667eea !important;
        border-color: #667eea !important;
    }

    .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #764ba2 !important;
        border-color: #764ba2 !important;
    }

    .fc-daygrid-day:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }

    .fc-event {
        border-color: #667eea !important;
    }
</style>
@endsection
