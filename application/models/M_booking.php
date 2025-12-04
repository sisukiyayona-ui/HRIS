<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_booking extends CI_Model{

    public function karyawan_view()
    {
        $query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, d.* from hris.karyawan k left join hris.bagian b on k.recid_bag = b.recid_bag left join hris.jabatan j on k.recid_jbtn = j.recid_jbtn left join hris.struktur s on s.recid_struktur = b.recid_struktur left join hris.department d on d.recid_department = b.recid_department  where k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

    public function karyawan_by_recid($recid_karyawan)
    {
        $query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, d.* from hris.karyawan k left join hris.bagian b on k.recid_bag = b.recid_bag left join hris.jabatan j on k.recid_jbtn = j.recid_jbtn left join hris.struktur s on s.recid_struktur = b.recid_struktur left join hris.department d on d.recid_department = b.recid_department  where k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and k.recid_karyawan = $recid_karyawan order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        return $query;
    }

public function view_booking()
{
    return $this->db->query("SELECT p.*, k.recid_karyawan, k.nama_karyawan, b.indeks_hr, r.nama_ruangan from booked p join hris.karyawan k on k.recid_karyawan = p.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag left join ruangan r on r.recid_ruangan = p.recid_ruangan where p.is_delete = '0' order by recid_book desc");
}

public function view_booking_date($tgl1, $tgl2)
{
    return $this->db->query("SELECT p.*, k.recid_karyawan, k.nama_karyawan, b.indeks_hr, r.nama_ruangan from booked p join hris.karyawan k on k.recid_karyawan = p.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag left join ruangan r on r.recid_ruangan = p.recid_ruangan where p.is_delete = '0' and ((waktu_mulai between '$tgl1' and '$tgl2') or (waktu_selesai between '$tgl1' and '$tgl2')) ");
}

    public function view_booking_comming($tgl)
    {
        return $this->db->query("SELECT p.*, k.recid_karyawan, k.nama_karyawan, b.indeks_hr, r.nama_ruangan from booked p join hris.karyawan k on k.recid_karyawan = p.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag left join ruangan r on r.recid_ruangan = p.recid_ruangan where p.is_delete = '0' and waktu_mulai >= '$tgl'");
    }

    public function view_booking_today($tgl)
    {
        return $this->db->query("SELECT b.*, bi.*, br.nama_barang, r.nama_ruangan from booked b left join booked_item bi on bi.recid_book = b.recid_book join barang br on br.recid_barang = bi.recid_barang join ruangan r on r.recid_ruangan = b.recid_ruangan  where b.is_delete = '0' and  date(waktu_mulai)  = '$tgl'");
    }

public function get_ruangan()
{
    return $this->db->query("SELECT * from ruangan order by  is_delete, nama_ruangan asc");
}

    public function get_ruangan_aktif()
    {
        return $this->db->query("SELECT * from ruangan where is_delete = '0' order by nama_ruangan asc");
    }

public function ruangan_by_id($id)
{
    return $this->db->query("SELECT * from ruangan where recid_ruangan = $id");
}

public function save_ruangan($data)
{
    $this->db->insert("ruangan", $data);
}

public function update_ruangan($id, $data)
{
    $this->db->where("recid_ruangan", $id)->update("ruangan", $data);
}

/*----------------------------------------------------------------------------------*/

public function get_barang()
{
    return $this->db->query("SELECT * from barang where is_delete = '0'");
}

public function barang_by_id($id)
{
    return $this->db->query("SELECT * from barang where recid_barang = $id");
}

public function save_barang($data)
{
    $this->db->insert("barang", $data);
}

public function update_barang($id, $data)
{
    $this->db->where("recid_barang", $id)->update("barang", $data);
}

public function barang_ready($f_online)
{
    if($f_online == "Online")
    {
        $query = $this->db->query("SELECT * from barang br where recid_barang NOT IN (SELECT recid_barang from booked_item bi join booked b on b.recid_book = bi.recid_book where (bi.status = 'Booked' or bi.status = 'In Use')) and br.f_online != 'Offline';");
    }else{
        $query = $this->db->query("SELECT * from barang br where recid_barang NOT IN (SELECT recid_barang from booked_item bi join booked b on b.recid_book = bi.recid_book where (bi.status = 'Booked' or bi.status = 'In Use'));");  
  }
    return $query;
}

    public function barang_ready_tgl($f_online, $s_date, $e_date)
    {
        if ($f_online == "Online") {
            $query = $this->db->query("SELECT * from barang br where recid_barang NOT IN (SELECT recid_barang from booked_item bi join booked b on b.recid_book = bi.recid_book where (bi.status = 'Booked' or bi.status = 'In Use')) and br.f_online != 'Offline';");
        } else {
            $query = $this->db->query("SELECT * from barang br where recid_barang NOT IN (SELECT recid_barang from booked_item bi join booked b on b.recid_book = bi.recid_book where (bi.status = 'Booked' or bi.status = 'In Use'));");
        }
        return $query;
    }

    public function get_barang_online()
    {
        return $this->db->query("SELECT * from barang where f_online != 'Offline' and is_delete = '0'");
    }

    public function cek_book_items($s_date, $e_date)
    {
        $query = $this->db->query("SELECT distinct(bi.recid_barang) as recid_barang, br.nama_barang FROM booked b 
                JOIN booked_item bi on bi.recid_book = b.recid_book
                join barang br on br.recid_barang = bi.recid_barang where (waktu_mulai between '$s_date' and '$e_date') or (waktu_selesai between '$s_date' and '$e_date') and b.status != 'Finished' ");
        return $query;
    }

/*-------------------------------------------------------------------------------------*/

public function save_room_booked($data)
{
    $this->db->insert("booked", $data);
}

public function update_room_booked($data, $id)
{
    $this->db->where("recid_book", $id)->update("booked", $data);
}

public function save_item_booked($data)
{
    $this->db->insert("booked_item", $data);
}

//update by recid_book (header)
public function update_items_booked($data, $id)
{
    $this->db->where("recid_book", $id)->update("booked_item", $data);
}

//update by recid_book_item (detail)
public function update_item_booked($data, $id)
{
    $this->db->where("recid_book_item", $id)->update("booked_item", $data);
}

public function last_booking()
{
    $query = $this->db->query("SELECT * From booked order by recid_book desc limit 1");
    return $query;
}

public function list_booked_id($id)
{
    $query = $this->db->query("SELECT * from booked_item bi join barang b on b.recid_barang = bi.recid_barang where recid_book = $id and bi.is_delete = '0'");
    return $query;
}

public function get_booked_id($id)
{
    $query = $this->db->query("SELECT p.*, r.nama_ruangan, k.nama_karyawan, b.indeks_hr from booked p join hris.karyawan k on k.recid_karyawan = p.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag left join ruangan r on r.recid_ruangan = p.recid_ruangan where p.recid_book = $id;");
    return $query;
}

}

?>