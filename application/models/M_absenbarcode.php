<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_absenbarcode extends CI_Model
{

    public function save_absen_masuk($data)
    {
        $db2 = $this->load->database('absen', TRUE);
        $db2->insert('hadir_barcode', $data);
    }

    public function monitor_masuk($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        
        // Simplified query without jenis_absen join to test if this fixes the issue
        $query = $db2->query("SELECT h.*, b.indeks_hr, h.jam_masuk, k.nama_karyawan, k.nik from hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where tanggal = '$tgl' and lokasi_masuk = 'Industri' and is_closed = '0' and h.jam_masuk IS NOT NULL and h.jam_masuk != '00:00:00' order by recid_absen desc limit 4");
        
        return $query;
    }

    public function monitor_masuk_baros($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        
        // Simplified query without jenis_absen join to match monitor_pulang approach
        $query = $db2->query("SELECT h.*, b.indeks_hr, h.jam_masuk, k.nama_karyawan, k.nik from hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where tanggal = '$tgl' and lokasi_masuk = 'Baros' and is_closed = '0' and h.jam_masuk IS NOT NULL and h.jam_masuk != '00:00:00' order by recid_absen desc limit 4");
        
        return $query;
    }

    public function monitor_pulang($tgl)
    {
        $tgl_kemaren = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT h.*, b.indeks_hr, h.jam_masuk, h.jam_keluar, k.nama_karyawan, k.nik from hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$tgl_kemaren' and '$tgl') and lokasi_pulang = 'Industri' and jam_keluar != '00:00:00' and is_closed = '0' order by mdf_date desc limit 4");
        return $query;
    }

    public function monitor_pulang_baros($tgl)
    {
        $tgl_kemaren = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT h.*, b.indeks_hr, h.jam_masuk, h.jam_keluar, k.nama_karyawan, k.nik from hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$tgl_kemaren' and '$tgl') and lokasi_pulang = 'Baros' and jam_keluar != '00:00:00' and is_closed = '0' order by mdf_date desc limit 4");
        return $query;
    }

    public function cek_double($recid_karyawan, $tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from hadir_barcode  where recid_karyawan = '$recid_karyawan' and tanggal = '$tgl' and is_closed = '0'");
        return $query;
    }

    public function update_hadir($data, $id)
    {
        $db2 = $this->load->database('absen', TRUE);
        $db2->where('recid_absen', $id);
        $db2->update('hadir_barcode', $data);
    }

    public function update_hadir2($recid_karyawan, $tgl, $data)
    {
        $db2 = $this->load->database('absen', TRUE);
        $db2->where('recid_karyawan', $recid_karyawan);
        $db2->where('tanggal', $tgl);
        $db2->update('hadir_barcode', $data);
    }

    public function delete_hadir($id)
    {
        $db2 = $this->load->database('absen', TRUE);
        $db2->where('recid_absen', $id);
        $db2->delete('hadir_barcode');
    }

    public function validasi_masuk_siang()
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT h.*, b.indeks_hr, ja.keterangan as nama_shift, ja.jam_in, h.jam_masuk, h.jam_keluar, k.nama_karyawan, k.nik , j.indeks_jabatan, ja2.jam_in as in_tmp from hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn  join jenis_absen ja on ja.recid_jenisabsen = h.status join jenis_absen ja2 on ja2.recid_jenisabsen = h.tmp_status where h.jam_masuk > ja2.jam_in and h.perlu_validasi = '1' order by jam_masuk asc");
        return $query;
    }

    public function cek_shift_malam_karyawan($karyawan, $tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from jadwal_shift s join jenis_absen ja on ja.recid_jenisabsen = s.recid_jenisabsen where recid_karyawan = $karyawan and tgl_kerja = '$tgl' and (s.recid_jenisabsen = 16 or s.recid_jenisabsen = 18 or s.recid_jenisabsen = 21)");
        return $query;
    }

    public function cek_shift_karyawan($karyawan, $tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from jadwal_shift s join jenis_absen ja on ja.recid_jenisabsen = s.recid_jenisabsen where recid_karyawan = $karyawan and tgl_kerja = '$tgl'");
        return $query;
    }

    public function scan_masuk_malam($karyawan, $tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from hadir_barcode where recid_karyawan = $karyawan and tanggal = '$tgl' and jam_masuk >= '20:30:00'");
        return $query;
    }

    public function hadir_by_id($id)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where recid_absen = $id");
        return $query;
    }

    public function jenis_absen_by_kode($kode)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from jenis_absen ja  where jenis = '$kode'");
        return $query;
    }

    public function save_shift($data)
    {
        $db2 = $this->load->database('absen', TRUE);
        $db2->insert('jadwal_shift', $data);
    }

    public function shift_double($recid_karyawan, $tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from jadwal_shift  where recid_karyawan = $recid_karyawan and tgl_kerja = '$tgl'");
        return $query;
    }

    public function update_shift($data, $id)
    {
        $db2 = $this->load->database('absen', TRUE);
        $db2->where('recid_jadwal', $id);
        $db2->update('jadwal_shift', $data);
    }

    public function data_shift_date($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT s.*, k.nik, k.nama_karyawan,  b.indeks_hr, j.indeks_jabatan, ja.jenis, ja.keterangan from jadwal_shift s join hris.karyawan k on k.recid_karyawan = s.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join jenis_absen ja on ja.recid_jenisabsen = s.recid_jenisabsen where s.tgl_kerja = '$tgl'");
        return $query;
    }

    public function validasi_izin_tgl($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT i.*, k.nik, k.recid_karyawan, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where perlu_validasi = '1' and tgl_izin = '$tgl' and  jenis = 'Keluar' and (lokasi_masuk = 'Industri' or lokasi_keluar = 'Industri') order by izin_recid desc");
        return $query;
    }

    public function validasi_izin_baros_tgl($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT i.*, k.nik, k.recid_karyawan, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where perlu_validasi = '1' and tgl_izin = '$tgl' and  jenis = 'Keluar' and (lokasi_masuk = 'Baros' or lokasi_keluar = 'Baros') order by izin_recid desc");
        return $query;
    }

    public function validasi_pulang_cepat()
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT i.*, h.*, b.indeks_hr, ja.keterangan as nama_shift, ja.jam_out, h.jam_keluar, k.nama_karyawan, k.nik , j.indeks_jabatan, ja2.jam_out as out_tmp from hadir_barcode h join izin i on i.recid_karyawan = h.recid_karyawan join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join jenis_absen ja on ja.recid_jenisabsen = h.status join jenis_absen ja2 on ja2.recid_jenisabsen = h.tmp_status where h.jam_keluar < ja2.jam_out and i.tgl_izin = h.tanggal and i.perlu_validasi = '1' and i.jenis = 'Pulang' and i.is_delete = '0' group by h.recid_absen order by jam_masuk asc");
        return $query;
    }

    public function validasi_pulang_cepat2()
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT i.*, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from izin i 
        join hris.karyawan k on k.recid_karyawan = i.recid_karyawan
        join hris.bagian b on b.recid_bag = k.recid_bag
        join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
        where i.perlu_validasi = '1' and i.jenis = 'Pulang' and i.is_delete = '0';");
        return $query;
    }

    public function hadir_today($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.recid_bag, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, j.recid_jbtn, ja.jenis, ja.keterangan, g.nama_golongan from hadir_barcode a 
			join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			left join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			left join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join jenis_absen ja on ja.recid_jenisabsen = a.status
			join hris.golongan g on g.recid_golongan = k.recid_golongan 
			where tanggal = '$tgl' and is_closed = '0' order by b.indeks_hr, k.nama_karyawan asc");
        return $query;
    }

    public function absen_sebelah($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.recid_bag, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, j.recid_jbtn, ja.jenis, ja.keterangan, g.nama_golongan from hadir_barcode a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join jenis_absen ja on ja.recid_jenisabsen = a.status
			join hris.golongan g on g.recid_golongan = k.recid_golongan 
			where tanggal = '$tgl' and (jam_masuk is null or jam_keluar is null) and is_closed = '0' order by b.indeks_hr, k.nama_karyawan asc");
        return $query;
    }

    public function hadir_today_bagian($tgl, $bagian)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.recid_bag, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, j.recid_jbtn, ja.jenis, ja.keterangan, g.nama_golongan from hadir_barcode a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join hris.golongan g on g.recid_golongan = k.recid_golongan
			join jenis_absen ja on ja.recid_jenisabsen = a.status 
			where tanggal = '$tgl' and is_closed = '0' and $bagian order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function absen_sebelah_bagian($tgl, $bagian)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.recid_bag, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, j.recid_jbtn, ja.jenis, ja.keterangan, g.nama_golongan from hadir_barcode a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join hris.golongan g on g.recid_golongan = k.recid_golongan
			join jenis_absen ja on ja.recid_jenisabsen = a.status 
			where tanggal = '$tgl'  and (jam_masuk is null or jam_keluar is null) and is_closed = '0' and $bagian order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function tidak_hadir_today($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.*, b.indeks_hr, b.recid_bag, j.indeks_jabatan, j.recid_jbtn, j.sts_jabatan, g.nama_golongan, s.nama_struktur, d.nama_department, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2 from hris.karyawan k join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.golongan g on g.recid_golongan = k.recid_golongan join hris.struktur s on s.recid_struktur = b.recid_struktur join hris.department d on d.recid_department = b.recid_department left join hris.karyawan sa on s.pic_struktur = sa.recid_karyawan left join hris.karyawan ba on b.pic_bagian = ba.recid_karyawan where k.recid_karyawan not in (select h.recid_karyawan from hadir_barcode h where tanggal ='$tgl' and h.is_closed = '0') and k.sts_aktif = 'aktif'   order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function tidak_hadir_today_baros($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.*, b.indeks_hr, b.recid_bag, j.indeks_jabatan, j.recid_jbtn, j.sts_jabatan, g.nama_golongan, s.nama_struktur, d.nama_department, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2 from hris.karyawan k join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.golongan g on g.recid_golongan = k.recid_golongan join hris.struktur s on s.recid_struktur = b.recid_struktur join hris.department d on d.recid_department = b.recid_department left join hris.karyawan sa on s.pic_struktur = sa.recid_karyawan left join hris.karyawan ba on b.pic_bagian = ba.recid_karyawan where k.recid_karyawan not in (select h.recid_karyawan from hadir_barcode h where tanggal ='$tgl' and h.is_closed = '0') and k.sts_aktif = 'aktif'     order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function tidak_hadir_bagian($tgl, $bagian)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.*, b.indeks_hr, b.recid_bag, j.indeks_jabatan, j.recid_jbtn, j.sts_jabatan, g.nama_golongan, s.nama_struktur, d.nama_department, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2 from hris.karyawan k join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.golongan g on g.recid_golongan = k.recid_golongan join hris.struktur s on s.recid_struktur = b.recid_struktur join hris.department d on d.recid_department = b.recid_department left join hris.karyawan sa on s.pic_struktur = sa.recid_karyawan left join hris.karyawan ba on b.pic_bagian = ba.recid_karyawan where k.recid_karyawan not in (select h.recid_karyawan from hadir_barcode h where tanggal ='$tgl' and h.is_closed = '0' ) and k.sts_aktif = 'aktif'   and ($bagian) order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function jml_karyawan()
    {
        $query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan, j.sts_jabatan, g.nama_golongan, s.nama_struktur, d.nama_department, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2 FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join golongan g on g.recid_golongan = k.recid_golongan join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan where k.sts_aktif='Aktif' order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function jml_bagian($bagian)
    {
        $query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan, j.sts_jabatan, g.nama_golongan, s.nama_struktur, d.nama_department, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2 FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join golongan g on g.recid_golongan = k.recid_golongan join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan where k.sts_aktif='Aktif' and ($bagian) order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function jml_bagian_spm($bagian)
    {
        $query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan, j.sts_jabatan, g.nama_golongan, s.nama_struktur, d.nama_department, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2 FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join golongan g on g.recid_golongan = k.recid_golongan join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan where k.sts_aktif='Aktif'  and ($bagian) order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function jml_all($tgl) // kehadiran semua karyawan (baik hadir maupun tidak hadir)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' ");
        return $query;
    }

    public function jml_all_baros($tgl) // kehadiran semua karyawan (baik hadir maupun tidak hadir)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.recid_bag, b.indeks_hr, s.nama_struktur, d.nama_department, j.recid_jbtn, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, g.nama_golongan FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
            join hris.golongan g on g.recid_golongan = k.recid_golongan
			where tanggal = '$tgl'  ");
        return $query;
    }

    public function jml_all_bagian($tgl, $bagian) // kehadiran semua karyawan (baik hadir maupun tidak hadir)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl'  and ($bagian)");
        return $query;
    }


    public function jml_karyawan_baros()
    {
        $query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan, j.sts_jabatan, g.nama_golongan, s.nama_struktur, d.nama_department, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2 FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join golongan g on g.recid_golongan = k.recid_golongan join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan where k.sts_aktif='Aktif'   order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function jml_spm($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' ");
        return $query;
    }

    public function jml_by_status($tgl, $status)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and ($status)");
        return $query;
    }

    public function jml_by_status_spm($tgl, $status)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl')  and ($status)");
        return $query;
    }

    public function jml_by_status_baros($tgl, $status)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and ($status)");
        return $query;
    }

    public function jml_by_status_bagian($tgl, $status, $bagian)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl')  and ($status) and ($bagian)");
        return $query;
    }

    public function jml_by_status_bagian_spm($tgl, $status, $bagian)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl')  and ($status) and ($bagian)");
        return $query;
    }

    public function rekap_kehadiran($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT b.indeks_hr, s.nama_struktur, d.nama_department,count(k.recid_karyawan) as jml_karyawan,
			COUNT(IF(recid_jenisabsen = 1, 1, NULL)) AS K,
			COUNT(IF(recid_jenisabsen = 2, 1, NULL)) AS SID,
			COUNT(IF(recid_jenisabsen = 3, 1, NULL)) AS C, 
			COUNT(IF(recid_jenisabsen = 4, 1, NULL)) AS D, 
			COUNT(IF(recid_jenisabsen = 5, 1, NULL)) AS P1, 
			COUNT(IF(recid_jenisabsen = 6, 1, NULL)) AS H1, 
			COUNT(IF(recid_jenisabsen = 7, 1, NULL)) AS H2, 
			COUNT(IF(recid_jenisabsen = 8, 1, NULL)) AS WFH,
			COUNT(IF(recid_jenisabsen = 9, 1, NULL)) AS MS,
			COUNT(IF(recid_jenisabsen = 10, 1, NULL)) AS A,
			COUNT(IF(recid_jenisabsen = 11, 1, NULL)) AS M, 
			COUNT(IF(recid_jenisabsen = 12, 1, NULL)) AS P4, 
			COUNT(IF(recid_jenisabsen = 13, 1, NULL)) AS TOS, 
			COUNT(IF(recid_jenisabsen = 14, 1, NULL)) AS S1, 
			COUNT(IF(recid_jenisabsen = 15, 1, NULL)) AS S2, 
			COUNT(IF(recid_jenisabsen = 16, 1, NULL)) AS S3,
			COUNT(IF(recid_jenisabsen = 17, 1, NULL)) AS LS1,
			COUNT(IF(recid_jenisabsen = 18, 1, NULL)) AS LS2,
			COUNT(IF(recid_jenisabsen = 19, 1, NULL)) AS SS1, 
			COUNT(IF(recid_jenisabsen = 20, 1, NULL)) AS SS2, 
			COUNT(IF(recid_jenisabsen = 21, 1, NULL)) AS SS3, 
			COUNT(IF(recid_jenisabsen = 22, 1, NULL)) AS SPM1, 
			COUNT(IF(recid_jenisabsen = 23, 1, NULL)) AS SPM2, 
			COUNT(IF(recid_jenisabsen = 24, 1, NULL)) AS L,
			COUNT(IF(recid_jenisabsen = 25, 1, NULL)) AS KK,
			COUNT(IF(recid_jenisabsen = 26, 1, NULL)) AS MS1,
			COUNT(IF(recid_jenisabsen = 27, 1, NULL)) AS MS2,
			COUNT(IF(recid_jenisabsen = 28, 1, NULL)) AS OT,
			COUNT(IF(recid_jenisabsen = 29, 1, NULL)) AS KR,
			COUNT(IF(recid_jenisabsen = 30, 1, NULL)) AS MS2R,
			COUNT(IF(recid_jenisabsen = 31, 1, NULL)) AS OT1,
			COUNT(IF(recid_jenisabsen = 32, 1, NULL)) AS OT2,
			COUNT(IF(recid_jenisabsen = 33, 1, NULL)) AS OT3,
			COUNT(IF(recid_jenisabsen = 34, 1, NULL)) AS MU,
			COUNT(IF(recid_jenisabsen = 35, 1, NULL)) AS GH,
			COUNT(IF(recid_jenisabsen = 36, 1, NULL)) AS P,
			COUNT(IF(recid_jenisabsen = 37, 1, NULL)) AS MS4
			FROM master_absen.hadir_barcode h 
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			where TANGGAL = '$tgl' group by b.indeks_hr");
        return $query;
    }

    public function rekap_kehadiran_bagian($tgl, $bagian)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT b.indeks_hr, s.nama_struktur, d.nama_department, count(k.recid_karyawan) as jml_karyawan,
			COUNT(IF(recid_jenisabsen = 1, 1, NULL)) AS K,
			COUNT(IF(recid_jenisabsen = 2, 1, NULL)) AS SID,
			COUNT(IF(recid_jenisabsen = 3, 1, NULL)) AS C, 
			COUNT(IF(recid_jenisabsen = 4, 1, NULL)) AS D, 
			COUNT(IF(recid_jenisabsen = 5, 1, NULL)) AS P1, 
			COUNT(IF(recid_jenisabsen = 6, 1, NULL)) AS H1, 
			COUNT(IF(recid_jenisabsen = 7, 1, NULL)) AS H2, 
			COUNT(IF(recid_jenisabsen = 8, 1, NULL)) AS WFH,
			COUNT(IF(recid_jenisabsen = 9, 1, NULL)) AS MS,
			COUNT(IF(recid_jenisabsen = 10, 1, NULL)) AS A,
			COUNT(IF(recid_jenisabsen = 11, 1, NULL)) AS M, 
			COUNT(IF(recid_jenisabsen = 12, 1, NULL)) AS P4, 
			COUNT(IF(recid_jenisabsen = 13, 1, NULL)) AS TOS, 
			COUNT(IF(recid_jenisabsen = 14, 1, NULL)) AS S1, 
			COUNT(IF(recid_jenisabsen = 15, 1, NULL)) AS S2, 
			COUNT(IF(recid_jenisabsen = 16, 1, NULL)) AS S3,
			COUNT(IF(recid_jenisabsen = 17, 1, NULL)) AS LS1,
			COUNT(IF(recid_jenisabsen = 18, 1, NULL)) AS LS2,
			COUNT(IF(recid_jenisabsen = 19, 1, NULL)) AS SS1, 
			COUNT(IF(recid_jenisabsen = 20, 1, NULL)) AS SS2, 
			COUNT(IF(recid_jenisabsen = 21, 1, NULL)) AS SS3, 
			COUNT(IF(recid_jenisabsen = 22, 1, NULL)) AS SPM1, 
			COUNT(IF(recid_jenisabsen = 23, 1, NULL)) AS SPM2, 
			COUNT(IF(recid_jenisabsen = 24, 1, NULL)) AS L,
			COUNT(IF(recid_jenisabsen = 25, 1, NULL)) AS KK,
			COUNT(IF(recid_jenisabsen = 26, 1, NULL)) AS MS1,
			COUNT(IF(recid_jenisabsen = 27, 1, NULL)) AS MS2,
			COUNT(IF(recid_jenisabsen = 28, 1, NULL)) AS OT,
			COUNT(IF(recid_jenisabsen = 29, 1, NULL)) AS KR,
			COUNT(IF(recid_jenisabsen = 30, 1, NULL)) AS MS2R,
			COUNT(IF(recid_jenisabsen = 31, 1, NULL)) AS OT1,
			COUNT(IF(recid_jenisabsen = 32, 1, NULL)) AS OT2,
			COUNT(IF(recid_jenisabsen = 33, 1, NULL)) AS OT3,
			COUNT(IF(recid_jenisabsen = 34, 1, NULL)) AS MU,
            COUNT(IF(recid_jenisabsen = 35, 1, NULL)) AS GH,
			COUNT(IF(recid_jenisabsen = 36, 1, NULL)) AS P,
			COUNT(IF(recid_jenisabsen = 37, 1, NULL)) AS MS4
			FROM master_absen.hadir_barcode h 
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			where TANGGAL = '$tgl' and $bagian group by b.indeks_hr");
        return $query;
    }

    public function data_adjust($recid_karyawan, $tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT h.*, k.recid_karyawan, k.nama_karyawan, k.nik, b.indeks_hr, j.indeks_jabatan FROM `hadir_barcode` h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where h.tanggal = '$tgl' and k.recid_karyawan = $recid_karyawan;");
        return $query;
    }

    public function karyawan_by_recid($recid_karyawan)
    {
        $query = $this->db->query("SELECT  k.recid_karyawan, k.nama_karyawan, k.nik, b.indeks_hr, j.indeks_jabatan FROM hris.karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where k.recid_karyawan = $recid_karyawan;");
        return $query;
    }

    public function cek_p1_periode($recid_karyawan, $tgl_mulai, $tgl_exp)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * FROM absensi_hris where jenis_absen = 5 and tanggal between '$tgl_mulai' and '$tgl_exp' and recid_karyawan = $recid_karyawan;");
        return $query;
    }

    public function cek_puasa()
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from param_cint where recid_param = 1 and nilai = '1'");
        return $query;
    }

    public function hadir_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from hadir_barcode where recid_karyawan = $recid_karyawan and tanggal between '$tgl_mulai' and $tgl_akhir and is_closed = '0'");
        return $query;
    }

    public function shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, $status)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from hadir_barcode where recid_karyawan = $recid_karyawan and tanggal between '$tgl_mulai' and '$tgl_akhir' and status = $status and is_closed = '0'");
        return $query;
    }

    public function hitung_kerja($mulai, $sampai, $recid_karyawan)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join jenis_absen ja on ja.recid_jenisabsen = h.status where k.recid_karyawan = $recid_karyawan and (tanggal between '$mulai' and '$sampai') and absen_group = 'Hadir' and is_closed = '0' and (status != 24 and status != 28 and status != 31 and status != 32 and status != 33 and status != 2 and status != 3) order by tanggal");
        return $query;
    }

    function hitung_izin($mulai, $sampai, $recid_karyawan, $jenis)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * FROM `izin` where recid_karyawan = $recid_karyawan and tgl_izin between '$mulai' and '$sampai' and jenis = '$jenis' and is_delete = '0';");
        return $query;
    }

    public function hitung_mangkir($mulai, $sampai, $recid_karyawan)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where k.recid_karyawan = $recid_karyawan and (tanggal between '$mulai' and '$sampai') and status = 11 and is_closed = '0'");
        return $query;
    }

    public function cek_ja_hadir($recid_karyawan, $tgl_kerja)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from hadir_barcode h join jenis_absen ja on ja.recid_jenisabsen = h.status where tanggal = '$tgl_kerja'and recid_karyawan = $recid_karyawan and is_closed = '0';");
        return $query;
    }

    public function adjust_durasi($awal, $akhir)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT * from izin where tgl_izin between '$awal' and '$akhir' and is_delete = '0' and perlu_validasi = '0'");
        return $query;
    }
}
