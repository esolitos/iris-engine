<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends ESO_Controller
{
	var $view_data = array();
	
	function __construct()
	{
		parent::__construct();
		
		if( ! $this->session->userdata('logged_in'))
		{
			redirect("admin/login.html?dest=admin/main");
			exit();
		}

		$this->view_data = init_viewdata();
		$this->view_data['user'] = $this->session->all_userdata();
	}
	
	
	function index($w_id=FALSE)
	{
		$id = (is_numeric($w_id)) ? $w_id : $this->view_data['user']['user_website'];
		$user_id = $this->view_data['user']['user_id'];
		
		$this->view_data['website'] = $this->websites_model->load_website_data($id);
		
		output(array(
				'header' => init_headdata(),
				'footer' => TRUE,
				'admin/main_view' => $this->view_data
				)); 

	}
	
	function logged()
	{
		$this->view_data['message'] = "You're successfully logged in!";
		$this->index();
	}
	
}

/* End of file index.php */
/* Location: ./application/controllers/admin/index.php */