<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter extends CI_Controller
{
	var $user = array();
	var $view_data = array();

	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata(array(
			'form_attrib' => array(),
			'form_hidden' => array(),
			'stilesheets' => array(),
			));

		$this->load->model('newsletter_model');
		$this->view_data['options'] = explode(',', $this->input->get('options'));
		
		// Load the language.
		$this->lang->load('error', CURR_LANG);
		$this->lang->load('services/common', CURR_LANG);
		$this->lang->load('services/newsletter', CURR_LANG);
	}


	function index($w_id=FALSE)
	{
		if($w_id AND subscription_status($w_id, 'newsletter'))
		{
			if($this->input->post('submit') && subscription_status($w_id, 'newsletter'))
			{
				$w_id = $this->input->post('user_website');
				$input = $this->input->post();
				
				$this->form_validation->set_message('required', 'Devi inserire %s');
				$this->form_validation->set_message('valid_email', 'Devi inserire una email valida.');
				$this->form_validation->set_message('matches', 'Le email devono corrispondere.');
				
				// Setting-up library "FormValidation"
				$form_config = array(
					array('field' => 'name',				'label' => 'il nome',			'rules' => 'required'),
					array('field' => 'surname',				'label' => 'il Cognome',		'rules' => 'required'),
					array('field' => 'email',				'label' => 'i\'indirizzo email','rules' => 'required|valid_email|matches[email_check]'),
					array('field' => 'law_confirmation',	'label' => 'l\'accettazzione del trattamento dei dati personali', 'rules' => 'required'),
					);

				$this->load->helper('form');
				$this->form_validation->set_rules($form_config);

				if($this->form_validation->run())
				{
					$result = $this->newsletter_model->userAdd($w_id, $input['email'], $input['name'], $input['surname']);

					if( isset($result->error))
						$this->view_data['error'] = $result->error;
					else
						$this->view_data['message'] = "{$input['name']} ({$input['email']}) &egrave; iscritto correttamente.";
					
					$this->view_data['title'] = "Conferma Sottoscrizione";
					$this->view_data['css'] = $this->websites_model->get_style($w_id, SERVICE_ID_BOOKING);

					$this->load->view('common/message_view', $this->view_data);
					return TRUE;
				}
			}
			$this->view_data['form_attrib']['id'] = 'newsletter_subscription';
			$this->view_data['form_hidden']['user_website'] = $w_id;

			output(array(
				'common/simple_header_view' => $this->view_data,
				'newsletter/registration_view.php' => $this->view_data,
				'common/simple_footer_view' => $this->view_data,
				));
		}
		else
		{
			show_404('reservations/index');
		}
	}
}


/* End of file websites.php */
/* Location: ./application/controllers/admin/websites.php */