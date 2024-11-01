$(document).ready(function() {
    function loadTotalAnggota() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-anggota').text(data.totalAnggota);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

    function loadTotalKegiatan() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-kegiatan').text(data.totalKegiatan);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

    function loadTotalRapat() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-rapat').text(data.totalRapat);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }
    
    function loadUpcomingEvents() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.timelines.length > 0) {
                    let eventsHtml = '<ul>';
                    data.timelines.forEach(function(event) {
                        eventsHtml += `<li>${event.judul_kegiatan} ( ${event.tanggal_kegiatan} )</li>`;
                    });
                    eventsHtml += '</ul>';
                    $('#upcoming-events').html(eventsHtml);
                } else {
                    $('#upcoming-events').text('Tidak ada kegiatan mendatang.');
                }
            },
            error: function() {
                $('#upcoming-events').text('Terjadi kesalahan saat mengambil data kegiatan mendatang.');
            }
        });
    }
    
    function loadLatestMeetings() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.rapatDilaksanakan.length > 0) {
                    let meetingsHtml = '<ul>';
                    data.rapatDilaksanakan.forEach(function(meeting) {
                        meetingsHtml += `<li>${meeting.judul} ( ${meeting.tanggal} )</li>`;
                    });
                    meetingsHtml += '</ul>';
                    $('#latest-meetings').html(meetingsHtml);
                } else {
                    $('#latest-meetings').text('Tidak ada rapat yang sudah dilaksanakan.');
                }
            },
            error: function() {
                $('#latest-meetings').text('Terjadi kesalahan saat mengambil data rapat yang sudah dilaksanakan.');
            }
        });
    }

    // Load dashboard data on page load
    loadTotalAnggota();
    loadTotalKegiatan();
    loadTotalRapat();
    loadUpcomingEvents();
    loadLatestMeetings();
});