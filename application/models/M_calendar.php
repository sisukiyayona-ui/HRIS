<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_calendar extends CI_Model{

public function get_events($start, $end)
{
    // return $this->db->where("start >=", $start)->where("end <=", $end)->where("is_delete =", '0')->get("calendar_events");
    $query = $this->db->query("SELECT c.*, b.*, r.*, c.is_delete as cal_del, b.is_delete as book_del FROM calendar_events c 
left join booked b on b.recid_book = c.recid_booking
left join ruangan r on r.recid_ruangan = b.recid_ruangan
where start >= '$start' and end <= '$end' order by start asc");
    return $query;
}

public function add_event($data)
{
    $this->db->insert("calendar_events", $data);
}

public function get_event($id)
{
    return $this->db->where("ID", $id)->get("calendar_events");
}

public function update_event($id, $data)
{
    $this->db->where("ID", $id)->update("calendar_events", $data);
}

public function delete_event($id)
{
    $this->db->where("ID", $id)->delete("calendar_events");
}

public function calendar_color($cat)
{
    return $this->db->query("SELECT * From calendar_color where category = '$cat'");
}

}

?>