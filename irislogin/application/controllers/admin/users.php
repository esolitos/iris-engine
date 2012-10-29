<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
{
	var $curr_uid = 0;
	var $curr_wid = 0;
	var $view_data = array();
	
	var $form_add_config = array(
		array(
			'field'   => 'username', 
			'label'   => 'Username', 
			'rules'   => 'required|alpha_dash|max_length[35]'
			),
		array(
			'field'   => 'email', 
			'label'   => 'User\'s email', 
			'rules'   => 'required|valid_email'
			),
		);
		
	var $form_edit_config = array(
		array(
			'field'   => 'user_id', 
			'label'   => 'User', 
			'rules'   => 'required|numeric'
			),
		array(
			'field'   => 'action', 
			'label'   => 'Action', 
			'rules'   => 'required|alpha_dash'
			)
		);
	
	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata( array(
											'form_edit_hidden' => array(),
											'form_edit_attrib' => array(
												'id' => 'form_edit_user'
												),
											'form_add_hidden' => array(),
											'form_add_attrib' => array(
												'id' => 'form_add_user'
												),
											'all_users' => array(),
											'users_manager' => FALSE
											));
		
		$this->load->model('users_model', 'users_db');
		
		
		if( ! $this->session->userdata('logged_in') OR $this->session->userdata('user_id') != USER_ID_MASTER_ADMIN)
		{
			redirect("admin/login.html?dest=admin/users");
			exit();
		}
		
		$this->curr_uid = $this->session->userdata('user_id');
		$this->curr_wid = $this->session->userdata('user_website');
		
		$this->view_data['form_add_hidden']['user_website'] = $this->curr_wid;
		$this->view_data['form_edit_hidden']['user_website'] = $this->curr_wid;
	}
	
	function index($w_id=FALSE)
	{
		if(!$w_id)
			$this->view_data['all_users'] = $this->users_db->get_users_for_website($this->curr_wid);
		else
			$this->view_data['all_users'] = $this->users_db->get_users_for_website($w_id);
		
		output(array(
				'header' => init_headdata(),
				'footer' => TRUE,
				'users/admin_view' => $this->view_data
				));
	}
	
	function add($website_id=FALSE)
	{
		if( ! $this->input->post('submit') AND $website_id)
		{
			$this->view_data['form_add_hidden']['user_website'] = $website_id;
			$this->index();
		}
		else if($this->input->post('submit') AND $this->input->post('user_website'))
		{
			$this->load->helper('form');
			$this->form_validation->set_rules($this->form_add_config);
		
			if($this->form_validation->run() === FALSE)
			{
				$this->index();
			}
			else
			{
				$input = $this->input->post();
				unset($input['submit']);
			
				$result = $this->users_db->add($input['username'], $input['email'],  $input['user_website']);
				if($result['status']===TRUE)
				{
					$this->view_data['message'] = "User {$input['username']} correctly created with email {$input['email']}.";
					$this->index();
				}
				else
				{
					$this->view_data['error'] = "User {$input['username']} wasn't created correctly. <br/>Error: {$result['err_descr']}. Code:{$result['err_code']}.";
					$this->view_data['form_add_hidden']['user_website'] = $input['user_website'];
					$this->index();
				}
			}
		}
		else
		{
			$this->index();
		}
	}
	
	function edit()
	{
		$input = $this->input->post();
		
		$this->load->helper('form');
		$this->form_validation->set_rules($this->form_edit_config);
		if($this->form_validation->run() === FALSE)
		{
			$this->index();
		}
		else
		{
			switch ($input['action'])
			{
				case 'set_pass':
					if($this->users_db->update_pwd($input['user_id']))
						$this->view_data['exec_results'] = "User's passowrd was correctly reset.";
					else
						$this->view_data['exec_errors'][] = "User's passowrd wasn't correctly reset. Please try again, if the problem persists contact an administrator.";
					break;
				
				case 'delete':
					if($this->users_db->delete_user($input['user_id']))
						$this->view_data['message'] = "User was correctly deleted.";
					else
						$this->view_data['error'] = "Website's user permission was correctly removed, but i was unable to delete him/her from database.";
					break;
				
				default:
					show_error("The action not correct. Reload the page and if the problem persists please contact an dministrator!");
					break;
			}
			
			$this->index();
		}
	}
	
}


/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */