<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class visitor_model extends CI_Model {
 
  public function __construct() {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }
 
  // add
  public function tambah($visitor_data) {
    $this->db->insert('visitor', $visitor_data);
  }
 
  // Total visitor Website on Dashboard
  public function visit_dash() {
    $now = date('Y');
 
    $this->db->select('date, COUNT(*) AS total');
    $this->db->from('visitor');
    $this->db->group_by('date');
    $this->db->order_by('date', 'desc');
    $this->db->where('SUBSTR(date,1,4)', $now);
    $this->db->limit(14);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function count_visitor($page)
  {
    $query = $this->db->query("SELECT * FROM visitor where url = '$page'");
    return $query;
  }
 
 
}
 