<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_corsec extends CI_Model{

    public function docsecre_pinsert($data)
    {
        $db2 = $this->load->database('hris', TRUE);
        $db2->insert('doc_secre', $data);
    }

    public function docsecre_view()
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT * from doc_secre ds 
		join karyawan k on k.recid_karyawan = ds.recid_karyawan 
		join struktur d on d.recid_struktur = ds.recid_struktur
        join bagian b on b.recid_bag = k.recid_bag
		");
        return $query;
    }

    public function docsecre_view_struktur($recid_struktur)
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT * from doc_secre ds 
		join karyawan k on k.recid_karyawan = ds.recid_karyawan 
		join struktur d on d.recid_struktur = ds.recid_struktur
        join bagian b on b.recid_bag = k.recid_bag
        where ds.recid_struktur = $recid_struktur
		");
        return $query;
    }

    public function last_doc()
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT * from doc_secre ds order by recid_doc desc limit 1
		");
        return $query;
    }

    public function doc_by_id($recid_doc)
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT * from doc_secre ds 
		join karyawan k on k.recid_karyawan = ds.recid_karyawan 
		join struktur d on d.recid_struktur = ds.recid_struktur
		where recid_doc = $recid_doc
		");
        return $query;
    }

    public function struktur_by_karyawan($recid_karyawan)
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT k.recid_karyawan, k.nama_karyawan, b.recid_bag, s.recid_struktur , s.nama_struktur FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.recid_karyawan = '$recid_karyawan'");
        return $query;
    }

    public function karyawan_by_struktur($recid_str)
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT k.recid_karyawan, k.nama_karyawan, b.recid_bag, b.indeks_hr, s.recid_struktur , s.nama_struktur FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on j.recid_jbtn = k.recid_jbtn where s.recid_struktur = '$recid_str' and k.sts_aktif = 'aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function docsecre_tahun($tahun)
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT * from doc_secre where year(tanggal) = $tahun");
        return $query;
    }

    public function docsecre_pupdate($data, $id)
    {
        $db2 = $this->load->database('hris', TRUE);
        $db2->where('recid_doc', $id);
        $db2->update('doc_secre', $data);
    }

    public function karyawan_view()
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT k.*, s.nama_struktur, sa.nama_karyawan as atasan1, b.*, j.*, d.*, ba.nama_karyawan as atasan2 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department  where k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc")->result();
        return $query;
    }

    public function struktur_by_recid($recid_str)
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT * FROM struktur where recid_struktur = '$recid_str'")->result();
        return $query;
    }

    public function struktur_on_dok()
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT DISTINCT(s.recid_struktur) as recid_struktur, s.nama_struktur from doc_secre ds join struktur s on s.recid_struktur = ds.recid_struktur order by nama_struktur asc;");
        return $query;
    }

    public function struktur_view()
    {
        $db2 = $this->load->database('hris', TRUE);
        $query = $db2->query("SELECT s.*, k.nama_karyawan from struktur s left join karyawan k on k.recid_karyawan = s.pic_struktur where sis_delete = '0' order by nama_struktur asc");
        return $query;
    }
}