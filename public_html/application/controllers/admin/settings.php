<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller
{
	var $view_data = array();
	
	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();
		
		if( ! $this->session->userdata('logged_in'))
		{
			redirect("admin/login.html");
			exit();
		}
	}
	
	function index()
	{	
		redirect("admin");
	}

	function style($service, $remove=FALSE)
	{
		$website_id = $this->session->userdata('user_website');
		
		if($this->input->post('submit'))
		{
			$filename = $this->websites_model->get_style($website_id, $service)
			if($filename != FALSE AND $filename != STYLE_DEFAULT_FILE)
				$this->_delete_style($filename, $website_id, $service);

			$this->load->library('upload');
			if($this->upload->do_upload('css_file'))
			{
				$upload_info = $this->upload->data();

				$this->websites_model->add_style($website_id, $service, $upload_info['file_name']);
			}	

			redirect($this->input->post('from'));
			exit();
		}
		elseif($remove == 'remove')
		{
			$this->load->helper('file');
			$filename = $this->websites_model->get_style($website_id, $service);
			if($this->_delete_style($filename, $website_id, $service))
			{
					redirect($this->input->get('from'));
					exit();
			}
			show_error("Errore nell'eliminazione del file di stile.");
		}
		else
			show_404('admin/settings'.$service);
	}
	
	
	function color($service)
	{
		$this->load->model('settings_model');
		$website_id = $this->session->userdata('user_website');
		
		if($this->input->post('submit'))
		{
			$input = $this->input->post();
			unset($input['submit']);
			unset($input['from']);
			
			foreach ($input as $option_name => $value)
			{
				$option_id = $this->settings_model->getOptionID($option_name);
								
				if($value AND $option_id)
					$this->settings_model->updateOption($website_id, $service, $option_id, $value);
			}
			
			redirect($this->input->post('from'));
			exit();
		}
		else
			show_404('admin/settings'.$service);
		
	}
	
	
	
	
	
	
	
	function _delete_style($filename, $website_id, $service)
	{
		if(unlink(FCPATH.$filename))
		{
			return $this->websites_model->remove_style($website_id, $service);
		}
		
		return FALSE;
		
	}
}


/* End of file settings.php */
/* Location: ./application/controllers/admin/settings.php */