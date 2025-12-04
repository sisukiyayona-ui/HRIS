	// ################################################### LOOKUP METHODS FOR IMPORT ###################################################################

	/**
	 * Lookup jabatan by name
	 * 
	 * @param string $nama_jbtn Name of the jabatan
	 * @return object|null Jabatan object or null if not found
	 */
	public function jabatan_by_name($nama_jbtn)
	{
		$query = $this->db->query("SELECT * FROM jabatan WHERE nama_jbtn = ? AND is_delete = '0' LIMIT 1", array($nama_jbtn));
		$result = $query->row();
		return $result;
	}

	/**
	 * Lookup bagian by name
	 * 
	 * @param string $nama_bag Name of the bagian
	 * @return object|null Bagian object or null if not found
	 */
	public function bagian_by_name($nama_bag)
	{
		$query = $this->db->query("SELECT * FROM bagian WHERE nama_bag = ? AND is_delete = '0' LIMIT 1", array($nama_bag));
		$result = $query->row();
		return $result;
	}

	/**
	 * Lookup sub bagian by name
	 * 
	 * @param string $sub_bag Name of the sub bagian
	 * @return object|null Sub bagian object or null if not found
	 */
	public function sub_bagian_by_name($sub_bag)
	{
		$query = $this->db->query("SELECT * FROM bagian_sub WHERE sub_bag = ? AND is_delete = '0' LIMIT 1", array($sub_bag));
		$result = $query->row();
		return $result;
	}