<?php 
// Layout utama
if(!empty($content)){
	 $this->load->view('Layout/header');
	// $this->load->view('Layout/top');
	$this->load->view($content);
	$this->load->view('Layout/footer');
}else{
	$this->load->view('Layout/header');
	$this->load->view($kosong);	
}

?>