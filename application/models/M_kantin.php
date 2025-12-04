<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_kantin extends CI_Model
{

    public function karyawan_by_nik($nik)
    {
        $query = $this->db->query("SELECT * from karyawan  where nik = '$nik' and sts_aktif = 'Aktif' and cci = 'Tidak'");
        return $query;
    }

    public function karyawan_by_nik_flexible($nik)
    {
        // Support both old and new NIK formats
        // Escape the NIK for security
        $nik = $this->db->escape_str($nik);
        $query = $this->db->query("SELECT * from karyawan where nik = '$nik' and sts_aktif = 'Aktif' and cci = 'Tidak'");
        return $query;
    }


    public function karyawan_by_recid($id)
    {
        $query = $this->db->query("SELECT k.*, b.indeks_hr from karyawan k join bagian b on b.recid_bag = k.recid_bag  where recid_karyawan = '$id'");
        return $query;
    }

    public function all_koprasi()
    {
        $query = $this->db->query("SELECT * from tamu  where kategori = 'Koprasi' order by kategori asc");
        return $query;
    }

    public function all_outsource()
    {
        $query = $this->db->query("SELECT * from tamu  where kategori = 'Outsource' order by kategori asc");
        return $query;
    }

    public function all_jemputan()
    {
        $query = $this->db->query("SELECT * from tamu  where kategori = 'Jemputan' order by kategori asc");
        return $query;
    }

    public function all_kop_out_jem()
    {
        $query = $this->db->query("SELECT * from tamu  where kategori = 'Jemputan' or kategori = 'Outsource' or kategori = 'Koprasi' order by kategori asc");
        return $query;
    }

    public function all_kop_out()
    {
        $query = $this->db->query("SELECT * from tamu  where kategori = 'Outsource' or kategori = 'Koprasi' order by kategori asc");
        return $query;
    }

    public function all_tamu_pkl()
    {
        $query = $this->db->query("SELECT * from tamu  where kategori = 'Tamu' or kategori = 'PKL' order by kategori asc");
        return $query;
    }


    public function tamu_by_barcode($code)
    {
        $query = $this->db->query("SELECT * from tamu  where no_barcode = '$code'");
        return $query;
    }

    public function tamu_by_recid($code)
    {
        $query = $this->db->query("SELECT * from tamu  where guest_id = '$code'");
        return $query;
    }

    public function save_makan($data)
    {
        $this->db->insert('makan', $data);
    }

    public function cek_double($recid_karyawan, $tgl)
    {
        $query = $this->db->query("SELECT * from makan  where recid_karyawan = '$recid_karyawan' and tgl_makan = '$tgl'");
        return $query;
    }

    public function cek_double_tamu($recid_tamu, $tgl)
    {
        $query = $this->db->query("SELECT * from makan  where recid_tamu = '$recid_tamu' and tgl_makan = '$tgl'");
        return $query;
    }

    public function jml_all()
    {

        $query = $this->db->query("SELECT k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, j.sts_jabatan, g.nama_golongan, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 FROM karyawan k join hris.bagian b on b.recid_bag = k.recid_bag join hris.struktur s on s.recid_struktur = b.recid_struktur join hris.department d on d.recid_department = b.recid_department join hris.jabatan j on j.recid_jbtn = k.recid_jbtn left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function jml_by_status($tgl, $status)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, j.sts_jabatan, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1, h.*, ja.keterangan, ja.jenis
        FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
            left join hris.karyawan sa on s.pic_struktur = sa.recid_karyawan 
            left join hris.karyawan ba on b.pic_bagian = ba.recid_karyawan
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and (k.cci = 'Tidak' and k.spm = 'Tidak') and ($status) order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function karyawan_blm_makan($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
            where k.recid_karyawan not in (select m2.recid_karyawan from hris.makan m2 where tgl_makan = '$tgl' ) and tanggal = '$tgl' and (status = '1' or status = '4' or status = '9' or (status between 12 and 23)) and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function karyawan_blm_makani($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
            where k.recid_karyawan not in (select m2.recid_karyawan from hris.makan m2 where tgl_makan = '$tgl'  and lokasi = 'Industri' ) and tanggal = '$tgl' and (status = '1' or status = '4' or status = '9' or (status between 12 and 23)) and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function karyawan_blm_makanb($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_barcode h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
            where k.recid_karyawan not in (select m2.recid_karyawan from hris.makan m2 where tgl_makan = '$tgl' and lokasi = 'Baros' ) and tanggal = '$tgl' and (status = '1' or status = '4' or status = '9' or (status between 12 and 23)) and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function tamu_blm_makan($tgl)
    {
        $query = $this->db->query("SELECT t.*, t.kategori as penempatan from tamu t where guest_id not in (select m2.recid_tamu from makan m2 where tgl_makan = '$tgl' ) and (kategori = 'PKL' or kategori = 'Tamu') order by guest_id asc");
        return $query;
    }

    public function outsource_blm_makan($tgl)
    {
        $query = $this->db->query("SELECT * from tamu where guest_id not in (select m2.recid_tamu from makan m2 where tgl_makan = '$tgl' ) and (kategori = 'Outsource') order by guest_id asc");
        return $query;
    }

    public function koprasi_blm_makan($tgl)
    {
        $query = $this->db->query("SELECT t.*, t.kategori as penempatan from tamu t where guest_id not in (select m2.recid_tamu from makan m2 where tgl_makan = '$tgl' ) and (kategori = 'Koprasi' or kategori = 'Outsource') order by guest_id asc");
        return $query;
    }

    public function opsi_manuals()
    {
        $query = $this->db->query("SELECT * from enum_kantin order by nama_opsi desc");
        return $query;
    }

    public function belum_makan_top($tgl)
    {
        $query = $this->db->query("SELECT k.*, ma.status, b.indeks_hr, j.indeks_jabatan from karyawan k left join master_absen.hadir_barcode ma on ma.recid_karyawan = k.recid_karyawan join master_absen.jenis_absen ja on ja.recid_jenisabsen = ma.status left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur  left join department d on d.recid_department = b.recid_department  where k.recid_karyawan not in (select m2.recid_karyawan from makan m2 where tgl_makan = '$tgl' ) and ja.absen_group = 'Hadir' and ja.recid_jenisabsen != 24 and ma.tanggal = '$tgl' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and j.tingkatan >= 8 order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function all_flat($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan from makan m where tgl_makan = '$tgl' and manuals = '3' ");
        return $query;
    }

    public function all_manual($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan from makan m where tgl_makan = '$tgl' and manuals = '1' ");
        return $query;
    }

    public function all_scan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan from makan m where tgl_makan = '$tgl' and manuals = '0' ");
        return $query;
    }

    public function flat_karyawan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '3' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function flat_tamu($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '3'  and (m.kategori = 'Tamu' or m.kategori = 'PKL' or m.kategori = 'Koprasi' or m.kategori = 'Outsource') order by guest_name asc");
        return $query;
    }

    public function flat_tamui($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi = 'Industri' and manuals = '3'  and (m.kategori = 'Tamu' or m.kategori = 'PKL' or m.kategori = 'Koprasi' or m.kategori = 'Outsource') order by guest_name asc");
        return $query;
    }

    public function flat_tamub($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi = 'Baros' and manuals = '3'  and (m.kategori = 'Tamu' or m.kategori = 'PKL' or m.kategori = 'Koprasi' or m.kategori = 'Outsource') order by guest_name asc");
        return $query;
    }

    public function flat_koprasi($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '3'  and (m.kategori = 'Koprasi' or m.kategori = 'Outsource') order by guest_name asc");
        return $query;
    }

    public function flat_jemputan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, t.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '3'  and (m.kategori = 'Jemputan') order by guest_name asc");
        return $query;
    }

    public function flat_kupon_lainnya($tgl) // pengemudi & satpam chitose
    {
        $query = $this->db->query("SELECT k.*, ma.status, b.indeks_hr, j.indeks_jabatan from karyawan k left join master_absen.hadir_barcode ma on ma.recid_karyawan = k.recid_karyawan join master_absen.jenis_absen ja on ja.recid_jenisabsen = ma.status left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join department d on d.recid_department = b.recid_department where k.recid_karyawan not in (select m2.recid_karyawan from makan m2 where tgl_makan = '$tgl' ) and ja.absen_group = 'Hadir' and ja.recid_jenisabsen != 24 and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and ((b.recid_bag = 24 and (ja.recid_jenisabsen = 19 or ja.recid_jenisabsen = 1)) /* or b.recid_bag = 23 */)  and ma.tanggal = '$tgl' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc;");
        return $query;
    }

    public function flat_kupon_koprasi($tgl)
    {
        $query = $this->db->query("SELECT t.* from tamu t where t.guest_id not in (select m2.recid_tamu from makan m2 where tgl_makan = '$tgl' ) and kategori = 'Koprasi';");
        return $query;
    }

    public function flat_kupon_jemputan($tgl)
    {
        $query = $this->db->query("SELECT t.* from tamu t where t.guest_id not in (select m2.recid_tamu from makan m2 where tgl_makan = '$tgl' ) and kategori = 'Jemputan';");
        return $query;
    }

    public function flat_kupon_baros_outkop($tgl)
    {
        $query = $this->db->query("SELECT t.* from tamu t where t.guest_id not in (select m2.recid_tamu from makan m2 where tgl_makan = '$tgl' ) and ((kategori = 'Koprasi' and guest_id between 71 and 74) or (kategori = 'Outsource' and (guest_id = 65 or guest_id between 75 and 77)));");
        return $query;
    }

    public function data_makan_karyawan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_makan_karyawani($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and kategori = 'Karyawan' and lokasi = 'Industri' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_makan_karyawanb($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and kategori = 'Karyawan' and lokasi = 'Baros' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_makan_today($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan from makan m where tgl_makan = '$tgl' order by makan_id desc");
        return $query;
    }

    public function monitor_kantin($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan from makan m where tgl_makan = '$tgl' and (manuals = '1' or manuals = '0') and m.lokasi = 'Industri' order by makan_id desc limit 5");
        return $query;
    }

    public function monitor_kantin_baros($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan from makan m where tgl_makan = '$tgl' and (manuals = '1' or manuals = '0') and m.lokasi = 'Baros' order by makan_id desc limit 5");
        return $query;
    }

    public function data_makan_tamu($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and (m.kategori = 'Tamu' or m.kategori = 'PKL') order by guest_name asc");
        return $query;
    }

    public function data_makan_tamui($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi = 'Industri' and (m.kategori = 'Tamu' or m.kategori = 'PKL') order by guest_name asc");
        return $query;
    }

    public function data_makan_tamub($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi = 'Baros' and (m.kategori = 'Tamu' or m.kategori = 'PKL') order by guest_name asc");
        return $query;
    }

    public function data_makan_jemputan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and (m.kategori = 'Jemputan') order by guest_name asc");
        return $query;
    }

    public function data_makan_koprasi($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and (t.kategori = 'Koprasi' or t.kategori = 'Outsource') order by guest_name asc");
        return $query;
    }

    public function data_makan_koprasii($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi ='Industri' and (t.kategori = 'Koprasi' or t.kategori = 'Outsource') order by guest_name asc");
        return $query;
    }

    public function data_makan_koprasib($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi ='Baros' and (t.kategori = 'Koprasi' or t.kategori = 'Outsource') order by guest_name asc");
        return $query;
    }

    public function data_mess_karyawan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '2' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_mess_karyawani($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and lokasii = 'Industri' and manuals = '2' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_mess_karyawanb($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and lokasii = 'Baros' and manuals = '2' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_mess_tamu($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '2'  and (m.kategori = 'Tamu' or m.kategori = 'PKL') order by guest_name asc");
        return $query;
    }

    public function data_flat_karyawan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '3' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }


    public function data_flat_karyawani($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and lokasi = 'Industri' and manuals = '3' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_flat_karyawanb($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and lokasi = 'Baros' and manuals = '3' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }


    public function data_flat_tamu($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, m.kategori as kat, tgl_makan, t.guest_name, t.no_barcode, t.kategori, t.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '3' order by guest_name asc");
        return $query;
    }

    public function data_flat_tamui($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, m.kategori as kat, tgl_makan, t.guest_name, t.no_barcode, t.kategori, t.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi = 'Industri' and manuals = '3' order by guest_name asc");
        return $query;
    }

    public function data_flat_tamub($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, m.kategori as kat, tgl_makan, t.guest_name, t.no_barcode, t.kategori, t.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi = 'Baros' and manuals = '3' order by guest_name asc");
        return $query;
    }

    public function data_baros($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '3' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_manual_karyawan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '1' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_manual_karyawani($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and lokasi = 'Industri' and manuals = '1' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_manual_karyawanb($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and lokasi = 'Baros' and manuals = '1' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_manual_periode($tgl1, $tgl2)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where (tgl_makan between '$tgl1' and '$tgl2') and manuals = '1' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_manual_tamu($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '1'  and (m.kategori = 'Tamu' or m.kategori = 'PKL') order by guest_name asc");
        return $query;
    }

    public function data_manual_koprasi($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '1'  and (m.kategori = 'Koprasi' or m.kategori = 'Outsource' or m.kategori = 'Jemputan') order by guest_name asc");
        return $query;
    }

    public function data_manual_koprasii($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi = 'Industri' and manuals = '1'  and (m.kategori = 'Koprasi' or m.kategori = 'Outsource' or m.kategori = 'Jemputan') order by guest_name asc");
        return $query;
    }

    public function data_manual_koprasib($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and lokasi = 'Baros' and manuals = '1'  and (m.kategori = 'Koprasi' or m.kategori = 'Outsource' or m.kategori = 'Jemputan') order by guest_name asc");
        return $query;
    }

    public function data_koprasi($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, m.kategori as kat, tgl_makan, t.guest_name, t.no_barcode, t.kategori from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and t.kategori = 'Koprasi' order by guest_name asc");
        return $query;
    }

    public function data_jemputan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, m.kategori as kat, tgl_makan, t.guest_name, t.no_barcode, t.kategori from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and t.kategori = 'Jemputan' order by guest_name asc");
        return $query;
    }

    public function data_scan_karyawan($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.penempatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '0' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function data_scan_tamu($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '0'  and (m.kategori = 'Tamu' or m.kategori = 'PKL') order by guest_name asc");
        return $query;
    }

    public function data_scan_koprasi($tgl)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori, m.kategori as penempatan from makan m  join tamu t on m.recid_tamu = t.guest_id where tgl_makan = '$tgl' and manuals = '0'  and (m.kategori = 'Koprasi' or m.kategori = 'Outsource' or m.kategori = 'Jemputan') order by guest_name asc");
        return $query;
    }

    public function compare_absen_makan($tgl)
    {
        $query = $this->db->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, jb.indeks_jabatan, ha.tanggal, ha.status, ja.keterangan, m.crt_date as waktu_makan, tgl_makan, m.manuals, m.alasan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan jb on jb.recid_jbtn = k.recid_jbtn join master_absen.hadir_barcode ha on ha.recid_karyawan = k.recid_karyawan join master_absen.jenis_absen ja on ja.recid_jenisabsen = ha.status where m.recid_karyawan IN ( select h.recid_karyawan from master_absen.hadir_barcode h join master_absen.jenis_absen j on j.recid_jenisabsen = h.status where tanggal = '$tgl'  and j.absen_group = 'Tidak Hadir') and ha.tanggal = '$tgl' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and tanggal = tgl_makan;");
        return $query;
    }

    public function compare_absen_makan_periode($tgl1, $tgl2)
    {
        $query = $this->db->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, jb.indeks_jabatan, ha.tanggal, ha.status, ja.keterangan, ja.absen_group, m.crt_date as waktu_makan, tgl_makan,  m.manuals, m.alasan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan jb on jb.recid_jbtn = k.recid_jbtn join master_absen.hadir_barcode ha on ha.recid_karyawan = k.recid_karyawan join master_absen.jenis_absen ja on ja.recid_jenisabsen = ha.status where m.recid_karyawan IN ( select h.recid_karyawan from master_absen.hadir_barcode h join master_absen.jenis_absen j on j.recid_jenisabsen = h.status where (tanggal between '$tgl1' and '$tgl2')  and j.absen_group = 'Tidak Hadir') and (ha.tanggal between '$tgl1' and '$tgl2') and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and tanggal = tgl_makan and ja.absen_group = 'Tidak Hadir';");
        return $query;
    }

    public function compare_makan_absen($tgl)
    {
        $query = $this->db->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, jb.indeks_jabatan, ha.tanggal, ha.status, ja.keterangan, m.crt_date as waktu_makan, tgl_makan, m.manuals, m.alasan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan jb on jb.recid_jbtn = k.recid_jbtn join master_absen.hadir_barcode ha on ha.recid_karyawan = k.recid_karyawan join master_absen.jenis_absen ja on ja.recid_jenisabsen = ha.status where m.recid_karyawan NOT IN ( select h.recid_karyawan from master_absen.hadir_barcode h join master_absen.jenis_absen j on j.recid_jenisabsen = h.status where tanggal = '$tgl' and j.absen_group = 'Tidak Hadir') and ha.tanggal = '$tgl' and ha.tanggal = m.tgl_makan and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak';");
        return $query;
    }

    public function data_makan_karyawan_periode($tgl1, $tgl2)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, m.tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where (tgl_makan between '$tgl1' and '$tgl2') and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by  sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan, tgl_makan, waktu_makan asc");
        return $query;
    }

    public function data_makan_tamu_periode($tgl1, $tgl2)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori from makan m  join tamu t on m.recid_tamu = t.guest_id where (tgl_makan between '$tgl1' and '$tgl2') and (m.kategori = 'Tamu' or m.kategori = 'PKL') order by guest_name, waktu_makan asc");
        return $query;
    }

    public function data_makan_all_tamu_periode($tgl1, $tgl2)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.guest_name, t.no_barcode, t.kategori from makan m  join tamu t on m.recid_tamu = t.guest_id where (tgl_makan between '$tgl1' and '$tgl2') and (m.kategori = 'Tamu' or m.kategori = 'PKL' or m.kategori = 'Koprasi' or m.kategori = 'Outsource' or m.kategori = 'Jemputan') order by guest_name, waktu_makan asc");
        return $query;
    }

    public function data_makan_baros_periode($tgl1, $tgl2)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where (tgl_makan between '$tgl1' and '$tgl2') and kategori = 'Karyawan' and manuals = '3' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan, waktu_makan asc");
        return $query;
    }

    public function generate_makan_baros($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT h.*, j.tingkatan FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join jenis_absen ja on ja.recid_jenisabsen = h.status join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where tanggal = '$tgl' and penempatan = 'Baros' and ja.absen_group = 'Hadir' and ja.recid_jenisabsen != 24");
        return $query;
    }

    public function generate_makan_industri($tgl)
    {
        $db2 = $this->load->database('absen', TRUE);
        $query = $db2->query("SELECT h.*, j.tingkatan, ja.recid_jenisabsen FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join jenis_absen ja on ja.recid_jenisabsen = h.status join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where tanggal = '$tgl' and penempatan = 'Industri' and ja.absen_group = 'Hadir' and tingkatan < 8  and ja.recid_jenisabsen != 24");
        return $query;
    }

    public function delete_manual($id)
    {
        $query = $this->db->query("DELETE from makan where makan_id = $id");
        return $query;
    }

    public function dist_direksi($tgl, $manual)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and ((k.penempatan = 'Baros' and j.tingkatan > 9) or (k.penempatan = 'Industri' and j.tingkatan > 7)) and manuals = '$manual' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc;");
        return $query;
    }

    public function dist_baros($tgl, $manual)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '$manual' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and j.tingkatan <= 9 and penempatan = 'Baros' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function dist_industri($tgl, $manual)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '$manual' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and j.tingkatan <= 7 and (b.recid_bag != 24 and b.recid_bag != 23) and penempatan = 'Industri' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function dist_kopout($tgl, $manual)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.no_barcode, t.guest_name, t.kategori from makan m join tamu t on t.guest_id = m.recid_tamu where tgl_makan = '$tgl' and manuals = '$manual' and (t.kategori = 'Koprasi' or t.kategori = 'Outsource') order by guest_name, waktu_makan asc");
        return $query;
    }

    public function dist_keamanan($tgl, $manual)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '$manual' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and b.recid_bag = 24  order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function dist_pengemudi($tgl, $manual)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where tgl_makan = '$tgl' and manuals = '$manual' and kategori = 'Karyawan' and k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and b.recid_bag = 23  order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function dist_jemputan($tgl, $manual)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.no_barcode, t.guest_name, t.kategori from makan m join tamu t on t.guest_id = m.recid_tamu where tgl_makan = '$tgl' and manuals = '$manual' and (t.kategori = 'Jemputan') order by guest_name, waktu_makan asc");
        return $query;
    }

    public function dist_pkltamu($tgl, $manual)
    {
        $query = $this->db->query("SELECT m.*, m.crt_date as waktu_makan, tgl_makan, t.no_barcode, t.guest_name, t.kategori from makan m join tamu t on t.guest_id = m.recid_tamu where tgl_makan = '$tgl' and manuals = '$manual' and (t.kategori = 'PKL' or t.kategori = 'Tamu') order by guest_name, waktu_makan asc");
        return $query;
    }
}
