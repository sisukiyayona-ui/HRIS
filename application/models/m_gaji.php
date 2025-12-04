<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_gaji extends CI_Model
{
    protected $table = 'gaji';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ambil semua record gaji (opsional paging)
     * @param int|null $limit
     * @param int|null $offset
     * @return CI_DB_result
     */
    public function get_all($limit = null, $offset = null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('created_at', 'DESC');
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, array('id_gaji' => $id));
    }
}
