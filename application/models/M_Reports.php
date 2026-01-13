    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class M_Reports extends CI_Model {

        public function get_aggregated_attendance_report_data($date)
        {
            // This function will aggregate data by department for hierarchical report
            $query = $this->db->query("SELECT 
                COALESCE(NULLIF(b.nama_bag, ''), 'Tidak Ada Bagian') AS departemen,
                k.dl_idl AS dl_idl,
                COUNT(k.recid_karyawan) AS total,
                SUM(CASE WHEN k.sk_kary_tetap_nomor IS NOT NULL AND k.sk_kary_tetap_nomor != '' AND k.jenkel IN ('L', 'Laki - laki') THEN 1 ELSE 0 END) AS tetap_l_count,
                SUM(CASE WHEN k.sk_kary_tetap_nomor IS NOT NULL AND k.sk_kary_tetap_nomor != '' AND k.jenkel IN ('P', 'Perempuan') THEN 1 ELSE 0 END) AS tetap_p_count,
                SUM(CASE WHEN k.sk_kary_tetap_nomor IS NOT NULL AND k.sk_kary_tetap_nomor != '' THEN 1 ELSE 0 END) AS tetap_jml_count,
                SUM(CASE WHEN (k.sk_kary_tetap_nomor IS NULL OR k.sk_kary_tetap_nomor = '') AND k.jenkel IN ('L', 'Laki - laki') THEN 1 ELSE 0 END) AS kontrak_l_count,
                SUM(CASE WHEN (k.sk_kary_tetap_nomor IS NULL OR k.sk_kary_tetap_nomor = '') AND k.jenkel IN ('P', 'Perempuan') THEN 1 ELSE 0 END) AS kontrak_p_count,
                SUM(CASE WHEN k.sk_kary_tetap_nomor IS NULL OR k.sk_kary_tetap_nomor = '' THEN 1 ELSE 0 END) AS kontrak_jml_count,
                SUM(CASE WHEN a.waktu IS NOT NULL THEN 1 ELSE 0 END) AS hadir_count,
                SUM(CASE WHEN ia.recid_karyawan IS NOT NULL THEN 1 ELSE 0 END) AS izin_count,
                SUM(CASE WHEN a.waktu IS NULL AND ia.recid_karyawan IS NULL THEN 1 ELSE 0 END) AS alpa_count,
                SUM(CASE WHEN ia.jenis = 'Sakit' THEN 1 ELSE 0 END) AS sakit_count,
                SUM(CASE WHEN ia.jenis = 'Izin' THEN 1 ELSE 0 END) AS izin_count_detail,
                SUM(CASE WHEN ia.jenis IN ('Cuti Tahunan', 'Cuti') THEN 1 ELSE 0 END) AS cuti_tahunan_count,
                SUM(CASE WHEN ia.jenis = 'Cuti Khusus' THEN 1 ELSE 0 END) AS cuti_khusus_count,
                SUM(CASE WHEN ia.jenis = 'Cuti Melahirkan' THEN 1 ELSE 0 END) AS cuti_melahirkan_count,
                SUM(CASE WHEN ia.jenis = 'Cuti Nikah' THEN 1 ELSE 0 END) AS cuti_nikah_count,
                SUM(CASE WHEN ia.jenis = 'Cuti Haid' THEN 1 ELSE 0 END) AS cuti_haid_count,
                SUM(CASE WHEN k.sts_aktif = 'Tidak Aktif' THEN 1 ELSE 0 END) AS karyawan_off_count
                FROM hris.karyawan k
                LEFT JOIN hris.bagian b ON b.recid_bag = k.recid_bag
                LEFT JOIN master_finger.absensi a ON a.nik = k.nik AND DATE(a.waktu) = ? AND a.status IN (0,1)
                LEFT JOIN master_finger.izin_absen ia ON ia.recid_karyawan = k.recid_karyawan 
                    AND ? BETWEEN ia.tgl_mulai AND ia.tgl_selesai 
                    AND ia.is_delete = 0
                WHERE k.sts_aktif = 'Aktif'
                GROUP BY b.nama_bag, k.dl_idl
                ORDER BY COALESCE(b.indeks_hr, 999), b.nama_bag", array($date, $date));
            
            return $query->result_array();
        }
        
        public function get_department_budget($date)
        {
            $query = $this->db->query("SELECT 
                COALESCE(NULLIF(b.nama_bag, ''), 'Tidak Ada Bagian') AS departemen,
                COUNT(k.recid_karyawan) AS budget
                FROM hris.karyawan k
                LEFT JOIN hris.bagian b ON b.recid_bag = k.recid_bag
                WHERE k.sts_aktif = 'Aktif'
                GROUP BY b.nama_bag
                ORDER BY COALESCE(b.indeks_hr, 999), b.nama_bag");
            
            return $query->result_array();
        }
        
        public function get_daily_attendance_report_data($date)
        {
            $query = $this->db->query("SELECT 
                k.recid_karyawan,
                k.nik,
                k.nama_karyawan AS nama,
                k.jenkel AS jenis_kelamin,
                COALESCE(NULLIF(b.nama_bag, ''), 'Tidak Ada Bagian') AS departemen,
                j.nama_jbtn AS jabatan,
                CASE 
                    WHEN k.sk_kary_tetap_nomor IS NOT NULL AND k.sk_kary_tetap_nomor != '' THEN 'TETAP'
                    ELSE 'KONTRAK'
                END AS job_type,
                TIME(MIN(a.waktu)) AS jam_masuk,
                TIME(MAX(a.waktu)) AS jam_keluar,
                ia.jenis AS jenis_izin,
                CASE
                    WHEN MIN(a.waktu) IS NOT NULL THEN 'HADIR'
                    WHEN ia.recid_karyawan IS NOT NULL THEN 'IZIN'
                    ELSE 'ALPA'
                END AS status_kehadiran
                FROM hris.karyawan k
                LEFT JOIN hris.bagian b ON b.recid_bag = k.recid_bag
                LEFT JOIN hris.jabatan j ON j.recid_jbtn = k.recid_jbtn AND j.is_delete = '0'
                LEFT JOIN master_finger.absensi a ON a.nik = k.nik AND DATE(a.waktu) = ? AND a.status IN (0,1)
                LEFT JOIN master_finger.izin_absen ia ON ia.recid_karyawan = k.recid_karyawan 
                    AND ? BETWEEN ia.tgl_mulai AND ia.tgl_selesai 
                    AND ia.is_delete = 0
                WHERE k.sts_aktif = 'Aktif'
                GROUP BY k.recid_karyawan, k.nik, k.nama_karyawan, k.jenkel, b.nama_bag, j.nama_jbtn, k.sk_kary_tetap_nomor, ia.jenis, ia.recid_karyawan
                ORDER BY COALESCE(b.indeks_hr, 999), b.nama_bag, k.nama_karyawan", array($date, $date));
            
            return $query->result_array();
        }
        
        public function get_monthly_percentage_attendance_report_data($month)
        {
            // Calculate the start and end date of the month
            $start_date = $month . '-01';
            $end_date = date('Y-m-t', strtotime($start_date)); // Last day of the month
            
            // Query to get monthly attendance statistics
            $query = $this->db->query("SELECT 
                SUM(CASE WHEN ia.jenis = 'Sakit' THEN 1 ELSE 0 END) AS sakit_count,
                SUM(CASE WHEN ia.jenis = 'Izin' THEN 1 ELSE 0 END) AS izin_count,
                SUM(CASE WHEN ia.jenis = 'Mangkir' THEN 1 ELSE 0 END) AS mangkir_count,
                SUM(CASE WHEN ia.jenis = 'Cuti Khusus' THEN 1 ELSE 0 END) AS cuti_khusus_count,
                SUM(CASE WHEN ia.jenis IN ('Cuti Tahunan', 'Cuti') THEN 1 ELSE 0 END) AS cuti_tahunan_count,
                SUM(CASE WHEN ia.jenis = 'Cuti Nikah' THEN 1 ELSE 0 END) AS cuti_nikah_count,
                SUM(CASE WHEN ia.jenis = 'Cuti Sakit' THEN 1 ELSE 0 END) AS cuti_sakit_count,
                SUM(CASE WHEN ia.jenis = 'Cuti Melahirkan' THEN 1 ELSE 0 END) AS cuti_melahirkan_count,
                COUNT(CASE WHEN a.waktu IS NOT NULL THEN 1 END) AS hadir_count,
                COUNT(CASE WHEN k.sk_kary_tetap_nomor IS NOT NULL AND k.sk_kary_tetap_nomor != '' AND a.waktu IS NOT NULL THEN 1 END) AS hadir_sewing_count,
                COUNT(CASE WHEN (k.sk_kary_tetap_nomor IS NULL OR k.sk_kary_tetap_nomor = '') AND a.waktu IS NOT NULL THEN 1 END) AS hadir_non_sewing_count,
                COUNT(k.recid_karyawan) AS total_karyawan_count,
                COUNT(CASE WHEN k.sk_kary_tetap_nomor IS NOT NULL AND k.sk_kary_tetap_nomor != '' THEN 1 END) AS total_sewing_count,
                COUNT(CASE WHEN k.sk_kary_tetap_nomor IS NULL OR k.sk_kary_tetap_nomor = '' THEN 1 END) AS total_non_sewing_count,
                SUM(CASE WHEN k.sts_aktif = 'Tidak Aktif' THEN 1 ELSE 0 END) AS resign_total_count,
                SUM(CASE WHEN k.sts_aktif = 'Tidak Aktif' AND k.sk_kary_tetap_nomor IS NOT NULL AND k.sk_kary_tetap_nomor != '' THEN 1 ELSE 0 END) AS resign_sewing_count,
                SUM(CASE WHEN k.sts_aktif = 'Tidak Aktif' AND (k.sk_kary_tetap_nomor IS NULL OR k.sk_kary_tetap_nomor = '') THEN 1 ELSE 0 END) AS resign_non_sewing_count,
                SUM(CASE WHEN DATE(k.tgl_m_kerja) >= ? AND DATE(k.tgl_m_kerja) <= ? THEN 1 ELSE 0 END) AS baru_total_count,
                SUM(CASE WHEN DATE(k.tgl_m_kerja) >= ? AND DATE(k.tgl_m_kerja) <= ? AND k.sk_kary_tetap_nomor IS NOT NULL AND k.sk_kary_tetap_nomor != '' THEN 1 ELSE 0 END) AS baru_sewing_count,
                SUM(CASE WHEN DATE(k.tgl_m_kerja) >= ? AND DATE(k.tgl_m_kerja) <= ? AND (k.sk_kary_tetap_nomor IS NULL OR k.sk_kary_tetap_nomor = '') THEN 1 ELSE 0 END) AS baru_non_sewing_count,
                0 AS os_skill_total_count,
                0 AS os_skill_resign_count,
                0 AS os_skill_baru_count,
                0 AS os_non_skill_total_count,
                0 AS os_non_skill_resign_count,
                0 AS os_non_skill_baru_count
                FROM hris.karyawan k
                LEFT JOIN master_finger.izin_absen ia ON ia.recid_karyawan = k.recid_karyawan 
                    AND DATE(ia.tgl_mulai) >= ? AND DATE(ia.tgl_selesai) <= ?
                    AND ia.is_delete = 0
                LEFT JOIN master_finger.absensi a ON a.nik = k.nik AND DATE(a.waktu) >= ? AND DATE(a.waktu) <= ? AND a.status IN (0,1)
                WHERE (k.sts_aktif = 'Aktif' OR DATE(k.tgl_m_kerja) <= ?)", array($start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $end_date));
            
            $result = $query->row_array();
            
            // Calculate totals and percentages
            $total_karyawan = $result['total_karyawan_count'] > 0 ? $result['total_karyawan_count'] : 1; // Avoid division by zero
            
            // Calculate subtotal for absensi (Sakit + Ijin + Mangkir)
            $subtotal_absensi = $result['sakit_count'] + $result['izin_count'] + $result['mangkir_count'];
            $subtotal_absensi_pct = number_format(($subtotal_absensi / $total_karyawan) * 100, 2, ',', '');
            
            // Calculate subtotal for cuti
            $subtotal_cuti = $result['cuti_khusus_count'] + $result['cuti_tahunan_count'] + $result['cuti_nikah_count'] + $result['cuti_sakit_count'] + $result['cuti_melahirkan_count'];
            $subtotal_cuti_pct = number_format(($subtotal_cuti / $total_karyawan) * 100, 2, ',', '');
            
            // Calculate total absensi (subtotal absensi + subtotal cuti)
            $total_absensi = $subtotal_absensi + $subtotal_cuti;
            $total_absensi_pct = number_format(($total_absensi / $total_karyawan) * 100, 2, ',', '');
            
            // Format percentages with % sign
            $sakit_pct = number_format(($result['sakit_count'] / $total_karyawan) * 100, 2, ',', '') . '%';
            $izin_pct = number_format(($result['izin_count'] / $total_karyawan) * 100, 2, ',', '') . '%';
            $mangkir_pct = number_format(($result['mangkir_count'] / $total_karyawan) * 100, 2, ',', '') . '%';
            $cuti_khusus_pct = number_format(($result['cuti_khusus_count'] / $total_karyawan) * 100, 2, ',', '') . '%';
            $cuti_tahunan_pct = number_format(($result['cuti_tahunan_count'] / $total_karyawan) * 100, 2, ',', '') . '%';
            $cuti_nikah_pct = number_format(($result['cuti_nikah_count'] / $total_karyawan) * 100, 2, ',', '') . '%';
            $cuti_sakit_pct = number_format(($result['cuti_sakit_count'] / $total_karyawan) * 100, 2, ',', '') . '%';
            $cuti_melahirkan_pct = number_format(($result['cuti_melahirkan_count'] / $total_karyawan) * 100, 2, ',', '') . '%';
            
            // Return structured data for the report
            $report_data = array(
                'absensi' => array(
                    'sakit' => $result['sakit_count'],
                    'sakit_pct' => $sakit_pct,
                    'izin' => $result['izin_count'],
                    'izin_pct' => $izin_pct,
                    'mangkir' => $result['mangkir_count'],
                    'mangkir_pct' => $mangkir_pct
                ),
                'subtotal_absensi' => array(
                    'total' => $subtotal_absensi,
                    'pct' => $subtotal_absensi_pct . '%'
                ),
                'cuti' => array(
                    'khusus' => $result['cuti_khusus_count'],
                    'khusus_pct' => $cuti_khusus_pct,
                    'tahunan' => $result['cuti_tahunan_count'],
                    'tahunan_pct' => $cuti_tahunan_pct,
                    'nikah' => $result['cuti_nikah_count'],
                    'nikah_pct' => $cuti_nikah_pct,
                    'sakit' => $result['cuti_sakit_count'],
                    'sakit_pct' => $cuti_sakit_pct,
                    'melahirkan' => $result['cuti_melahirkan_count'],
                    'melahirkan_pct' => $cuti_melahirkan_pct
                ),
                'subtotal_cuti' => array(
                    'total' => $subtotal_cuti,
                    'pct' => $subtotal_cuti_pct . '%'
                ),
                'total_absensi' => array(
                    'total' => $total_absensi,
                    'pct' => $total_absensi_pct . '%'
                ),
                'hadir' => array(
                    'total' => $result['hadir_count'],
                    'sewing' => $result['hadir_sewing_count'],
                    'non_sewing' => $result['hadir_non_sewing_count']
                ),
                'karyawan' => array(
                    'total' => $result['total_karyawan_count'],
                    'sewing' => $result['total_sewing_count'],
                    'non_sewing' => $result['total_non_sewing_count']
                ),
                'resign' => array(
                    'total' => $result['resign_total_count'],
                    'sewing' => $result['resign_sewing_count'],
                    'non_sewing' => $result['resign_non_sewing_count']
                ),
                'baru' => array(
                    'total' => $result['baru_total_count'],
                    'sewing' => $result['baru_sewing_count'],
                    'non_sewing' => $result['baru_non_sewing_count']
                ),
                'os_skill' => array(
                    'total' => $result['os_skill_total_count'],
                    'resign' => $result['os_skill_resign_count'],
                    'baru' => $result['os_skill_baru_count']
                ),
                'os_non_skill' => array(
                    'total' => $result['os_non_skill_total_count'],
                    'resign' => $result['os_non_skill_resign_count'],
                    'baru' => $result['os_non_skill_baru_count']
                )
            );
            
            return $report_data;
        }
    }