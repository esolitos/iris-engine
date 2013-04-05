<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends ESO_Controller
{
	var $view_data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();
		
		$this->load->model('users_model', 'users_db');
		
		$this->view_data['form_login_attrib'] = array('id' => 'login_form');
		$this->view_data['form_login_hidden'] = array();
		
		$this->view_data['form_login_hidden']['dest'] = str_replace($this->config->item('url_suffix'), '',$this->input->get('dest'));
	}
	
	function index()
	{
		if($this->session->userdata('logged_in'))
		{
			redirect('admin/main');
			exit();
		}
		else
		{
			if($this->input->post('submit'))
			{
				$user = $this->input->post('username');
				$pass = $this->input->post('pass');
				$dest = ($this->input->post('dest')) ? $this->input->post('dest') : 'admin/main' ;


				if($this->users_db->login($user, $pass))
				{
					redirect($dest);
					exit;
				}
				else
				{
					$this->view_data['error'] = "<em><strong>Attenzione!</strong><br>Credenziali di accesso non valide.</em>";
				}
			}
			
			output(array(
					'header' => init_headdata(),
					'footer' => TRUE,
					'admin/login_view' => $this->view_data,
					));
		}
	}
	
	function false($reason)
	{
		switch ($reason) {
			case 'value':
				# code...
				break;
			
			default:
				$this->view_data['error'] = "<em><strong>Attenzione!</strong><br>Credenziali di accesso non valide.</em>";
				break;
		}

		$this->index();
	}


	function logout()
	{
		if($this->session->userdata('logged_in'))
		{
			$this->users_db->logout();
			$this->view_data['message'] = "<em><strong>Info</strong><br>Sei uscito correttamente dal sistema.</em>";
			$this->index();
		}
		else
		{
			redirect('admin/login');
			exit();
		}
	}
	
}


/* End of file login.php */
/* Location: ./application/controllers/admin/login.php */