<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reservations extends CI_Controller
{
	var $user = array();
	var $view_data = array();

	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();
		
		if( ! $this->session->userdata('logged_in'))
		{
			redirect("admin/login.html?dest=admin/reservations");
			exit();
		}
		
		$this->load->model('reservations_model');
	}
		
	function index()
	{
		$w_id = $this->session->userdata('user_website');

		$subscriptions = $this->websites_model->get_services_for_website($w_id, 'name');
		
		if(isset($subscriptions['booking']) && !$subscriptions['booking']->expired)
		{
			if(count($new_requests = $this->reservations_model->get_for($w_id, 'new')) > 0)
				$this->view_data['new_requests'] = $new_requests;
			
			if(count($old_requests = $this->reservations_model->get_for($w_id, 'old')) > 0)
				$this->view_data['old_requests'] = $old_requests;
			
			$this->view_data['requests'] = $this->reservations_model->get_for($w_id);

			if(($style = $this->websites_model->get_style($w_id, SERVICE_ID_BOOKING)) != FALSE)
				$this->view_data['css_file'] = $style;

			output(array(
					'header' => init_headdata(),
					'footer' => TRUE,
					'reservations/admin_view' => $this->view_data
					));
			return;
		}

		show_404('admin/reservations/index');
		return;
	}
	
	function show($id=FALSE)
	{
		if($id)
		{
			$this->view_data['book'] = $this->reservations_model->get_one($id);
			
			if($this->view_data['book'])
			{
				output(array(
						'header' => init_headdata(),
						'footer' => TRUE,
						'reservations/admin_single_view' => $this->view_data
						));
				
				return;
			}
		}
		
		show_404('admin/reservations/show');
		return;
	}
	
	function ajax($id=FALSE)
	{
		if($id)
		{
			$this->view_data['req'] = $this->reservations_model->get_one($id);
			
			if($this->view_data['req'])
			{
				$this->load->view("reservations/admin_ajax.php", $this->view_data);
				
				return;
			}
		}
		
		show_404('admin/reservations/show');
		return;
	}

	function confirm($req_id, $val=1)
	{
		if($this->input->is_ajax_request())
		{
			if($this->reservations_model->mask_as($req_id, 'confirmed', $val))
				echo "TRUE";
			else
				echo "FALSE";
		}
		else
			show_error("Accesso diretto non autrizzato.", 403);
	}

}


/* End of file websites.php */
/* Location: ./application/controllers/admin/websites.php */