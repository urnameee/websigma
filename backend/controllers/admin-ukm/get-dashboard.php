    <?php
    // Pastikan untuk memasukkan file koneksi database Anda
    require_once '/backend/controllers/koneksi.php';

    // Set header response sebagai JSON
    header('Content-Type: application/json');

    // Fungsi untuk mendapatkan data dashboard
    function getDashboardData($conn, $id_ukm = null) {
        $response = [];
        
        try {
            // Total Anggota
            $anggotaQuery = "SELECT COUNT(*) as total FROM keanggotaan_ukm WHERE id_ukm = ? AND tanggal_berakhir >= CURDATE()";
            $stmt = $conn->prepare($anggotaQuery);
            $stmt->execute([$id_ukm]);
            $response['totalAnggota'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total Kegiatan
            $kegiatanQuery = "SELECT COUNT(*) as total FROM timeline_ukm WHERE id_ukm = ? AND status = 'active'";
            $stmt = $conn->prepare($kegiatanQuery);
            $stmt->execute([$id_ukm]);
            $response['totalKegiatan'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total Pendaftar (belum diproses)
            $pendaftarQuery = "SELECT COUNT(*) as total FROM pendaftaran_ukm WHERE id_ukm = ? AND tahap_seleksi = 1";
            $stmt = $conn->prepare($pendaftarQuery);
            $stmt->execute([$id_ukm]);
            $response['totalPendaftar'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total Rapat
            $rapatQuery = "SELECT COUNT(*) as total FROM rapat r 
                        JOIN timeline_ukm t ON r.id_timeline = t.id_timeline 
                        WHERE t.id_ukm = ?";
            $stmt = $conn->prepare($rapatQuery);
            $stmt->execute([$id_ukm]);
            $response['totalRapat'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Upcoming Events (5 kegiatan terdekat)
            $eventsQuery = "SELECT judul_kegiatan, tanggal_kegiatan, waktu_mulai, waktu_selesai 
                        FROM timeline_ukm 
                        WHERE id_ukm = ? AND status = 'active' 
                        AND tanggal_kegiatan >= CURDATE()
                        ORDER BY tanggal_kegiatan ASC, waktu_mulai ASC 
                        LIMIT 5";
            $stmt = $conn->prepare($eventsQuery);
            $stmt->execute([$id_ukm]);
            $upcomingEvents = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $eventHtml = "<div class='event-item'>";
                $eventHtml .= "<h4>" . htmlspecialchars($row['judul_kegiatan']) . "</h4>";
                $eventHtml .= "<p>Tanggal: " . date('d/m/Y', strtotime($row['tanggal_kegiatan'])) . "</p>";
                $eventHtml .= "<p>Waktu: " . date('H:i', strtotime($row['waktu_mulai'])) . 
                            " - " . date('H:i', strtotime($row['waktu_selesai'])) . "</p>";
                $eventHtml .= "</div>";
                $upcomingEvents[] = $eventHtml;
            }
            $response['upcomingEvents'] = implode('', $upcomingEvents);

            // Latest Meetings (5 rapat terakhir)
            $meetingsQuery = "SELECT r.judul, r.tanggal, r.notulensi_path
                            FROM rapat r
                            JOIN timeline_ukm t ON r.id_timeline = t.id_timeline
                            WHERE t.id_ukm = ?
                            ORDER BY r.tanggal DESC
                            LIMIT 5";
            $stmt = $conn->prepare($meetingsQuery);
            $stmt->execute([$id_ukm]);
            $latestMeetings = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $meetingHtml = "<div class='meeting-item'>";
                $meetingHtml .= "<h4>" . htmlspecialchars($row['judul']) . "</h4>";
                $meetingHtml .= "<p>Tanggal: " . date('d/m/Y', strtotime($row['tanggal'])) . "</p>";
                if ($row['notulensi_path']) {
                    $meetingHtml .= "<a href='notulensi/" . htmlspecialchars($row['notulensi_path']) . 
                                "' target='_blank'>Lihat Notulensi</a>";
                }
                $meetingHtml .= "</div>";
                $latestMeetings[] = $meetingHtml;
            }
            $response['latestMeetings'] = implode('', $latestMeetings);

            // Set status response
            $response['status'] = 'success';
            $response['message'] = 'Data berhasil dimuat';

        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        return $response;
    }

    // Mendapatkan ID UKM dari session atau parameter
    session_start();
    $id_ukm = isset($_SESSION['id_ukm']) ? $_SESSION['id_ukm'] : null;

    // Jika tidak ada ID UKM di session, cek parameter GET
    if (!$id_ukm && isset($_GET['id_ukm'])) {
        $id_ukm = filter_var($_GET['id_ukm'], FILTER_VALIDATE_INT);
    }

    // Jika masih tidak ada ID UKM, kembalikan error
    if (!$id_ukm) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID UKM tidak ditemukan'
        ]);
        exit;
    }

    // Ambil dan kembalikan data dashboard
    echo json_encode(getDashboardData($conn, $id_ukm));
    ?>