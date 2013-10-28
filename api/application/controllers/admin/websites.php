<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Websites extends ESO_API_Controller
{
	var $user = array();
	var $view_data = array();
	var $head_data = array();

	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();
		$this->head_data = init_headdata();
		
		
		if( ! $this->session->userdata('logged_in'))
		{
			redirect("admin/login.html?dest=admin/websites");
			exit();
		}

		$this->view_data['user'] = $this->session->all_userdata();
		
		if( $this->session->userdata('user_id') != USER_ID_MASTER_ADMIN)
		{
			redirect('errors/403');
			exit;
		}
		$this->load->model('users_model', 'users_db');
		// $this->load->model('offers_model', 'offers_db');
		
	}
		
	function index()
	{
		$this->view_data['services'] = $this->websites_model->get_services();
		$this->view_data['websites'] = $this->websites_model->get_all(TRUE);
		$this->view_data['counters']['users'] = $this->users_db->count();
		$this->view_data['counters']['websites'] = count($this->view_data['websites']);
		
		// $this->load->view('websites/admin_view', $this->view_data);
		output(array(
				'header' => $this->head_data,
				'footer' => TRUE,
				'websites/admin_view' => $this->view_data
				));
	}
	
	// ----------Websites------------
	
	function add()
	{
		if($this->input->post('submit'))
		{
			$data = $this->input->post();

			// Setting-up library "FormValidation"
			$form_config = array(
								 array(
									'field'   => 'website_name', 
									'label'   => "Il nome del sito", 
									'rules'   => 'trim|required'
								 ),
								 array(
 									'field'   => 'website_email', 
 									'label'   => "L'indirizzo dell'azienda",
 									'rules'   => 'trim|valid_email|required'
								 ),
								 array(
									'field'   => 'website_url',
									'label'   => "L'URL del sito",
									'rules'   => 'trim|prep_url|valid_url|required'
								 ),
								);

			$this->form_validation->set_rules($form_config);
			$this->form_validation->set_message('required', '%s &egrave; obbligatorio.');
			$this->form_validation->set_message('valid_url', '%s deve contenere un URL valido.');

			if($this->form_validation->run() === FALSE)
			{
				$this->view_data['website_name'] = FALSE;
				$this->view_data['website_email'] = FALSE;
				$this->view_data['website_url'] = FALSE;

				output(array(
						'header' => $this->head_data,
						'footer' => TRUE,
						'websites/admin_add_view' => $this->view_data
						));
			}
			else
			{
				$res = $this->websites_model->add_website($data['website_name'], $data['website_url'], $data['website_email']);
				if($res['status'])
				{
					$this->view_data['message'] = "Website {$data['website_name']} was added to the database. It's now suggested to <strong>".anchor('admin/users/add/'.$res['user_website'], "add a user")."</strong> as Website Admin!";
					$this->index();
				}
				else
				{
					$this->view_data['message'] = "Website {$data['website_name']} <strong>was not added</strong> to the database. " .$res['error'];
					$this->index();
				}
			}
		}
		else
		{
			$this->view_data['website_name'] = FALSE;
			$this->view_data['website_email'] = FALSE;
			$this->view_data['website_url'] = FALSE;
			
			output(array(
					'header' => $this->head_data,
					'footer' => TRUE,
					'websites/admin_add_view' => $this->view_data
					));
		}
	}

	function edit($w_id)
	{
		if($this->input->post('submit'))
		{
			$data = $this->input->post();
			unset($data['submit']);

			$res = $this->websites_model->edit_website($w_id, $data);
			if($res['status'])
				$this->view_data['message'] = "Website {$data['website_name']} was correctly edited!";
			else
				$this->view_data['message'] = "Website {$data['website_name']} <strong>has not been changed</strong>!";

			$this->index();
		}
		else if($w_id)
		{
			$this->view_data['website_data'] = $website = $this->websites_model->load_website_data($w_id, TRUE);

			$this->view_data['website_name'] = $website['info']->website_name;
			$this->view_data['website_email'] = $website['info']->website_email;
			$this->view_data['website_url'] = $website['info']->website_url;

			$this->view_data['newsletter_key'] = $website['info']->newsletter_key;
			
			if ($this->input->is_ajax_request() AND $w_id)			
				$this->load->view('ajax/html/websites/admin_edit_view', $this->view_data);
			else
				output(array(
					'header' => $this->head_data,
					'footer' => TRUE,
					'websites/admin_add_view' => $this->view_data
					));
		}
	}
	
	function remove($w_id=FALSE)
	{
		// $w_id = $this->uri->rsegment(3);
		if($w_id)
		{
			if($this->websites_model->delete_website($w_id))
			{
				$this->view_data['message'] = "Website was removed correctly";
				$this->index();
			}
			else
			{
				$this->view_data['message'] = "There was an unexpected error removing the website. Try again or contact an admin.";
				$this->index();
			}
		}
	}
	
	
	// ----------Subscriptions------------
	
	function add_subscr($w_id=FALSE)
	{
		if(!$w_id)
			$w_id = $this->input->post('website_id');

		if($this->input->post('submit') AND $w_id)
		{
			$input = $this->input->post();
			$input['website_id'] = $w_id;
			unset($input['submit']);
			
			$res = $this->websites_model->add_service($input);
			if($res['status'])
				$this->view_data['message'] = "The subscription was correctly added!";
			else
				$this->view_data['message'] = "There was an error adding the subscription: {$res['error']}";
			
			$this->index();
		}
		else if($w_id)
		{
			$website = $this->websites_model->load_website_data($w_id, FALSE);
		
			$this->view_data['website'] = $website['info'];
			$this->view_data['services'] = $this->websites_model->get_services();
		
			$this->view_data['display'] = 'add_subscription';
			// $this->load->view('subscriptions/add_view', $this->view_data);
			
			output(array(
					'header' => $this->head_data,
					'footer' => TRUE,
					'subscriptions/add_view' => $this->view_data
					));
		}
		else
		{
			show_error("Accesso Negato", 403);
		}
	}
	
	function extend_subscr($w_id=FALSE, $serv_name=FALSE)
	{
		if( ! $w_id )
			$this->input->post('website_id');

		$website_data = $this->websites_model->load_website_data($w_id);

		if (array_key_exists($serv_name, $website_data['services']))
		{
			if($this->input->is_ajax_request() AND ! $this->input->post('submit'))
			{
				$this->view_data['website_id'] = $w_id;
				$this->view_data['subscr_expire'] = $website_data['services'][$serv_name]->service_expire;
				$this->view_data['website_name'] = $website_data['info']->website_name;
				$this->view_data['service_name'] = $serv_name;
				$this->load->view('ajax/html/subscriptions/admin_edit_view', $this->view_data);
			}
			else if($this->input->post('submit'))
			{
				$expire = $this->input->post('expire') ;
				$result = $this->websites_model->edit_service($serv_name, $w_id, $expire);

				if($result['status'])
					$this->view_data['message'] = "The {$website_data['info']->website_name}'s subscription to {$website_data['services'][$serv_name]->service_name} will now expire on {$this->input->post('expire')}!";
				else
					$this->view_data['message'] = $result['error']." (Edited: {$result['affected']})";

				$this->index();
			}
			else
			{
				$this->view_data['display'] = "extend_subscription";
				$this->view_data['website'] = $website_data['info'];
				$this->view_data['service'] = $website_data['services'][$serv_name];
				$this->view_data['subscr_expire'] = $website_data['services'][$serv_name]->service_expire;
				$this->view_data['form_attrib'] = array();

				output(array(
					'header' => $this->head_data,
					'footer' => TRUE,
					'subscriptions/extend_view' => $this->view_data
					));
			}

		}
		else
		{
			show_error("Parametri non Corretti", 200);
		}
	}
	
}


/* End of file websites.php */
/* Location: ./application/controllers/admin/websites.php */