<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reservations extends ESO_Controller
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
											'newsletter' => FALSE
										));

		$this->load->model('reservations_model');
		$this->view_data['options'] = explode(',', $this->input->get('options'));

		// Load the language.
		$this->lang->load('error', CURR_LANG);
		$this->lang->load('services/common', CURR_LANG);
		$this->lang->load('services/booking', CURR_LANG);
	}

	
	function index($w_id=FALSE)
	{
		if($w_id)
		{
			// if( $this->_subscription_status($w_id))
			{
				$this->view_data['newsletter'] = TRUE;
				$this->view_data['form_attrib']['id'] = 'reservation_request';
				$this->view_data['form_hidden']['website_id'] = $w_id;
				
				if(($style = $this->websites_model->get_style($w_id, SERVICE_ID_BOOKING)) != FALSE)
					$this->view_data['css'] = "/".$style;

				output(array(
					'common/simple_header_view' => $this->view_data,
					'reservations/reservations_view' => $this->view_data,
					'common/simple_footer_view' => $this->view_data,
					));
				return;
			}
		}

		show_404('reservations/index');
		return;
	}


	function send()
	{
		$w_id = $this->input->post('website_id');
		
		// if($this->input->post('submit'))
		if($this->input->post('submit') && $this->_subscription_status($w_id))
		{
			// Setting-up library "FormValidation"
			$this->lang->load('form_validation', CURR_LANG);
			$this->form_validation->set_message('_check_date',		$this->lang->line('_check_date'));
			$this->form_validation->set_message('required',			$this->lang->line('required'));
			$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('is_natural_no_zero'));
			$this->form_validation->set_message('valid_email',		$this->lang->line('valid_email'));
			$this->form_validation->set_message('matches',			$this->lang->line('matches'));
			
			$form_config = array(
				array('field' => 'law_confirmation',	'label' => $this->lang->line('field_law_confirmation'), 'rules' => 'required'),
				array('field' => 'name',				'label' => $this->lang->line('field_name'),				'rules' => 'required'),
				array('field' => 'surname',				'label' => $this->lang->line('field_surname'),			'rules' => 'required'),
				array('field' => 'tel',					'label' => $this->lang->line('field_tel'),				'rules' => 'required'),
				array('field' => 'email',				'label' => $this->lang->line('field_email'),			'rules' => 'required|valid_email|matches[email_check]'),
				array('field' => 'from_date',			'label' => $this->lang->line('field_from_date'),		'rules' => 'required|callback__check_date'),
				array('field' => 'to_date',				'label' => $this->lang->line('field_to_date'),			'rules' => 'required|callback__check_date'),
				array('field' => 'adults',				'label' => $this->lang->line('field_adults'),			'rules' => 'required|is_natural_no_zero'),

				// those below are just for the form data refill
				array('field' => 'email_check',	'label' => '',	'rules' => ''),
				array('field' => 'babies',		'label' => '',	'rules' => ''),
				array('field' => 'notes',		'label' => '',	'rules' => ''),
				);

			// $this->load->helper('form');
			$this->form_validation->set_rules($form_config);
			
			// Loading email library;
			$this->load->library('email');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->index($w_id);
				return;
			}
			else // The form was submitted correctly
			{
				$input = $this->_clean_post_data( $this->input->post(), array('submit', 'email_check', 'law_confirmation'));

				$w_data = $this->websites_model->load_website_data($w_id, FALSE);
				
				
				if( ! isset($input['newsletter']))
				{
					$input['newsletter'] = 'no';
				}
				else
				{
					#Subscribe to newsletter
					$this->load->model('newsletter_model');
					$this->newsletter_model->userAdd($w_id, $input['email'], $input['name'], $input['surname']);
					$input['newsletter'] = 'si';
				}

				$message = $this->_build_mail($input);

				$this->email->to($w_data['info']->website_email);
				$this->email->bcc(IRIS_MAIL); 
				$this->email->from(ENGINE_MAIL, 'IrisLogin - Servizio Booking');
				$this->email->reply_to($input['email'], "{$input['name']} {$input['surname']}");
				$this->email->subject("Richiesta di prenotazione di {$input['name']} {$input['surname']} - IrisLogin");
				$this->email->message($message['html']);
				$this->email->set_alt_message($message['plain']);

				if ($this->email->send())
				{
					$input['email_sent'] = 1;
					$this->reservations_model->add_reservation($input);
					$this->view_data['message'] = $this->lang->line('request_sent_now_wait');
				}
				else
					$this->view_data['error'] = $this->lang->line('ERR_SENDING');
				
				$this->view_data['title'] = $this->lang->line('title_booking_service');
				$this->view_data['css'] = $this->websites_model->get_style($w_id, SERVICE_ID_GALLERY);
				$this->load->view('common/message_view', $this->view_data);
				return;
			}
		}

		show_404('reservations/send');
		return;
	}



	private function _subscription_status($w_id)
	{
		$subscr = $this->websites_model->get_services_for_website($w_id, 'name');
		
		if (isset($subscr['booking']))
			return ! $subscr['booking']->expired;
		else
			return FALSE;
	}
	
	private function _clean_post_data($array, $remove=array())
	{
		foreach($remove as $needle)
			if(isset($array[$needle]))
				unset($array[$needle]);
		
		return $array;
	}
	
	private function _build_mail($data)
	{
		$out = array();
		$now = date('r');
		$out['plain'] =<<<EOM
NOTE: This mail is shown in plain text becouse your client doesn't support HTML visualization. For a better expierence we suggest to use a client that support HTML mails.	

Subject: Request of booking from your website.

----------- Contact Details
Name: {$data['name']}
Surname: {$data['surname']}
Email: {$data['email']}
Tel: {$data['tel']}

----------- Booking Request Details

From {$data['from_date']} to {$data['to_date']}.
Number of adults: {$data['adults']}
Babies: {$data['babies']}

Notes:
{$data['notes']}

Request sended on: {$now}
Subscribed to Newsletter: {$data['newsletter']}

EOM;

	foreach($data as $name=>$info)
		$out['plain'] .= "{$name}: {$info}";

	$out['html'] = $this->load->view('mail/reservation_view', array( 'data' => $data), TRUE);
	
	return $out;
	}

	public function _check_date($date)
	{
		//match the format of the date
		if (preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $date, $parts))
			//check weather the date is valid of not
			return checkdate($parts[2],$parts[1],$parts[3]);

		return false;
	}
	
}


/* End of file websites.php */
/* Location: ./application/controllers/admin/websites.php */