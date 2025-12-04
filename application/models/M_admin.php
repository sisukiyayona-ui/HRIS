<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model{

	public function get_struktur()
	{
		$query = $this->db->query("SELECT * from hris.struktur where sis_delete = 0");
		return $query;
	}

	public function get_department()
	{
		$query = $this->db->query("SELECT distinct(d.nama_department) from hris.department d join hris.bagian b on b.recid_department = d.recid_department");
		return $query;
	}

	public function str_by_dept($dept)
	{
		$query = $this->db->query("SELECT b.*, s.nama_struktur FROM hris.bagian b 
			join hris.struktur s on b.recid_struktur = s.recid_struktur 
			join hris.department d on d.recid_department = b.recid_department
			where d.nama_department = '$dept' group by s.nama_struktur ");
		return $query;
	}

	public function str_name($str)
	{
		$query = $this->db->query("SELECT * from hris.struktur s where s.recid_struktur = $str");
		return $query;
	}

	public function birthday_list()
	{
		$query = $this->db->query("SELECT k.*,day(tgl_lahir) as tgl from hris.karyawan k join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where month(tgl_lahir) = month(CURRENT_DATE()) and sts_aktif = 'Aktif' and spm = 'Tidak' and tingkatan >= 6 order by tgl asc");
		return $query;
	}

	public function all_birthday_list()
	{
		$query = $this->db->query("SELECT k.*,day(tgl_lahir) as tgl, month(tgl_lahir) as bulan, j.indeks_jabatan, b.indeks_hr from hris.karyawan k join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.bagian b on b.recid_bag = k.recid_bag where month(tgl_lahir) = month(CURRENT_DATE()) and sts_aktif = 'Aktif' and spm = 'Tidak' order by tgl asc");
		return $query;
	}

	/*----------------------------- ARTICLE - BULLETIN ---------------------------------------------------------*/

	public function input_article($data)
	{
		$this->db->insert('article', $data);
	}

	public function update_article($data, $id)
	{
		$this->db->where('recid_article',$id);
		$this->db->update('article', $data);
	}

	public function article_publish()
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='article'  and status = 'publish' order by recid_article desc");
		return $query;
	}

	public function list_article()
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='article' order by recid_article desc");
		return $query;
	}

	public function list_article_kategori($kategori)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='article' and status = '$kategori' order by recid_article desc");
		return $query;
	}

	public function all_article()
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='article' order by recid_article desc");
		return $query;
	}

	public function random_article()
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where status = 'publish' order by RAND() limit 3");
		return $query;
	}

	public function all_image()
	{
		$query = $this->db->query("SELECT * FROM article where status = 'publish' order by recid_article desc");
		return $query;
	}

	public function article_pagging($limit, $offset)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='article' order by recid_article desc limit $limit, $offset");
		return $query;
	}

	public function article_pagging_kategori($limit, $offset, $kategori)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='article' and status = '$kategori' order by recid_article desc limit $limit, $offset");
		return $query;
	}

	public function article_pagging_publish($limit, $offset)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='article' and status = 'publish' order by recid_article desc limit $limit, $offset");
		return $query;
	}


	public function list_bulletin()
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='bulletin' order by recid_article desc");
		return $query;
	}

	public function list_bulletin_kategori($kategori)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='bulletin' and status = '$kategori' order by recid_article desc");
		return $query;
	}

	public function bulletin_publish()
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='bulletin' and status = 'publish' order by recid_article desc");
		return $query;
	}

	public function bulletin_pagging($limit, $offset)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='bulletin' order by recid_article desc limit $limit, $offset");
		return $query;
	}

	public function bulletin_pagging_kategori($limit, $offset, $kategori)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='bulletin' and status = '$kategori' order by recid_article desc limit $limit, $offset");
		return $query;
	}

	public function bulletin_pagging_publish($limit, $offset)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='bulletin' and status = 'publish' order by recid_article desc limit $limit, $offset");
		return $query;
	}

	public function list_csr_kategori($kategori)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='csr' and status = '$kategori' order by recid_article desc");
		return $query;
	}

	public function csr_publish()
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='csr' and status = 'publish' order by recid_article desc");
		return $query;
	}

	public function csr_pagging($limit, $offset)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='csr' order by recid_article desc limit $limit, $offset");
		return $query;
	}

	public function csr_pagging_kategori($limit, $offset, $kategori)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='csr' and status = '$kategori' order by recid_article desc limit $limit, $offset");
		return $query;
	}

	public function csr_pagging_publish($limit, $offset)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where type='csr' and status = 'publish' order by recid_article desc limit $limit, $offset");
		return $query;
	}


	public function article_id($id)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where recid_article = $id");
		return $query;
	}

	public function article_str($str)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where a.recid_struktur = $str and type='Article' and status = 'publish' order by recid_article desc");
		return $query;
	}

	public function bulletin_str($str)
	{
		$query = $this->db->query("SELECT a.*, s.nama_struktur FROM article a join hris.struktur s on a.recid_struktur = s.recid_struktur where a.recid_struktur = $str and type='Bulletin' and status = 'publish' order by recid_article desc");
		return $query;
	}

	public function count_visitor($page)
	{
		$query = $this->db->query("SELECT count(id_visitor) FROM visitor where url ='$page'");
		return $query();
	}

	/*----------------------------- GALLERY ---------------------------------------------------------*/

	public function gallery()
	{
		$query = $this->db->query("SELECT g.*, s.nama_struktur from gallery g join hris.struktur s on g.recid_bag = s.recid_struktur  order by recid desc");
		return $query;
	}

	public function last_gallery()
	{
		$query = $this->db->query("SELECT * from gallery g  order by recid desc limit 1");
		return $query;
	}

	public function gallery_publish()
	{
		$query = $this->db->query("SELECT g.*, s.nama_struktur from gallery g join hris.struktur s on g.recid_bag = s.recid_struktur where status= 'publish'  order by recid desc");
		return $query;
	}

	public function input_gallery($data)
	{
		$this->db->insert('gallery', $data);
	}

	public function input_photo($data)
	{
		$this->db->insert('photo_gallery', $data);
	}

	public function update_gallery($data, $id)
	{
		$this->db->where('recid',$id);
		$this->db->update('gallery', $data);
	}

	public function update_photo($data, $id)
	{
		$this->db->where('recid_photo',$id);
		$this->db->update('photo_gallery', $data);
	}

	public function gallery_id($id)
	{
		$query = $this->db->query("SELECT g.*, s.nama_struktur from gallery g join hris.struktur s on g.recid_bag = s.recid_struktur where recid = $id");
		return $query;
	}

	public function gallery_photo_id($id)
	{
		$query = $this->db->query("SELECT pg.* from photo_gallery pg join gallery g on g.recid = pg.recid_gallery where pg.recid_gallery = $id and is_active = '1'");
		return $query;
	}

	public function gallery_str($str)
	{
		$query = $this->db->query("SELECT g.*, s.nama_struktur from gallery g join hris.struktur s on g.recid_bag = s.recid_struktur where recid_bag = $str  and status = 'publish' and is_delete = '0' order by recid desc");
		return $query;
	}

	/*----------------------------- PROJECT ---------------------------------------------------------*/

	public function project()
	{
		$query = $this->db->query("SELECT g.*, s.nama_struktur from project g join hris.struktur s on g.recid_str = s.recid_struktur order by recid desc");
		return $query;
	}

	public function input_project($data)
	{
		$this->db->insert('project', $data);
	}

	public function update_project($data, $id)
	{
		$this->db->where('recid',$id);
		$this->db->update('project', $data);
	}

	public function project_id($id)
	{
		$query = $this->db->query("SELECT g.*, s.nama_struktur from project g join hris.struktur s on g.recid_str= s.recid_struktur where recid = $id");
		return $query;
	}

	public function project_str($str)
	{
		$query = $this->db->query("SELECT g.*, s.nama_struktur from project g join hris.struktur s on g.recid_str= s.recid_struktur where recid_str = $str and status = 'publish public' order by recid desc");
		return $query;
	}

	/*----------------------------- CALENDAR ---------------------------------------------------------*/

	public function get_calendar()
	{
		$query = $this->db->query("SELECT * FROM calendar_events where is_delete = '0'");
		return $query;
	}

	public function all_calendar()
	{
		$query = $this->db->query("SELECT * FROM calendar_events");
		return $query;
	}

	public function get_calendar_id($id)
	{
		$query = $this->db->query("SELECT * FROM calendar_events where ID = $id");
		return $query;
	}

	public function input_event($data)
	{
		$this->db->insert('calendar_events', $data);
	}

	public function detail_event($tgl)
	{
		$query = $this->db->query("SELECT * FROM `calendar_events` where start like '%$tgl%' or end like '%$tgl%'");
		return $query;
	}

	public function update_event($data, $id)
	{
		$this->db->where('id',$id);
		$this->db->update('calendar_events', $data);
	}

}