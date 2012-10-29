<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error_Manager extends CI_Controller
{
	var $user = array();
	var $view_data = array();
	var $head_data = array();

	function __construct()
	{
		parent::__construct();
		
		$this->view_data = init_viewdata();
		$this->head_data = init_headdata();

	}
	
	function index($code=0)
	{
		switch ($code) {
			case '403':
				output(array(
						'header' => $this->head_data,
						'footer' => TRUE,
						'error' => array('head' => "Autorization Error", 'message' => "Chi ti credi di essere! Non sei autorizzato a vedere questa pagina.", 'code' => 403 )
						));
				break;
			
			default:
				output(array(
						'header' => $this->head_data,
						'footer' => TRUE,
						'error' => array('head' => "General Error", 'message' => "I criceti che gestiscono server hanno rotto qualcosa, abbiamo già avvisato l'edicolante sotto casa. Riprova piu tardi!", 'code' => 500 )
						));
				break;
		}
	}
	
	
	function issue()
	{
		if( ! $this->session->userdata('logged_in'))
		{
			show_404();
		}
		elseif( ! $this->input->post())
		{
			redirect('admin/main');
		}
		else
		{
			$from_uri = $this->input->post('from');
			
			// Setting-up library "FormValidation"
			$this->form_validation->set_message('required', '%s &egrave; obbligatorio.');
			$this->form_validation->set_message('valid_email', '%s deve contenere un email valida.');
			$this->form_validation->set_message('min_length', '%s deve essere di almeno %d caratteri.');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-error span4"><a class="close" data-dismiss="alert" href="#">&times;</a>', '</div>');
			

			$validation = array(
				array(
					'field'   => 'username', 
					'label'   => 'Il Nome Utente', 
					'rules'   => 'required',
					),
				array(
					'field'   => 'name', 
					'label'   => 'Il tuo Nome', 
					'rules'   => 'required'
					),
				array(
					'field'   => 'company', 
					'label'   => 'L\'Azienda', 
					'rules'   => 'required'
					),
				array(
					'field'   => 'email',
					'label'   => 'L\'email',
					'rules'   => 'required|valid_email'
					),
				array(
					'field'   => 'tel',
					'label'   => 'Il numero di telefono',
					'rules'   => 'required'
					),
				array(
					'field'   => 'contact_reason',
					'label'   => 'Il motivo del contatto',
					'rules'   => 'required'
					),
				array(
					'field'   => 'message',
					'label'   => 'Il messaggio',
					'rules'   => 'required|min_length[50]'
					)
				);

			$this->load->helper('form');
			$this->form_validation->set_rules($validation);
			
			if ($this->form_validation->run() === FALSE) {
				$this->view_data['form_hidden'] = array();
				
				if($from_uri != FALSE)
					$this->view_data['form_hidden']['from'] = $from_uri;
					$this->view_data['form_hidden']['username'] = $this->input->post('username');
				output(array(
						'header' => $this->head_data,
						'footer' => TRUE,
						'common/contact_form_view' => $this->view_data
						));
			}
			else
			{
				$this->load->library('email');
				$input = $this->input->post();
				$email = $this->load->view('mail/client_issue_view', array('data' => $this->input->post()), TRUE);

				switch ($input['contact_reason']) {
					case 'admin':
						$this->email->subject("Richiesto Supporto dall'utente: {$input['username']} - IrisLogin.");
						break;
				
					case 'ask':
						$this->email->subject("Domanda dall'utente: {$input['username']} - IrisLogin.");
						break;
							
					case 'tech':
						$this->email->subject("Richiesto Supporto Tecnico dall'utente: {$input['username']} - IrisLogin.");
						break;
						
					case 'suggest':
						$this->email->subject("Suggerimento dall'utente: {$input['username']} - IrisLogin.");
						break;
						
					default:
						$this->email->subject("Messaggio dall'utente: {$input['username']} - IrisLogin.");
				}
				
				$this->email->to(IRIS_MAIL);
				$this->email->from(IRIS_MAIL, "IRIS Login");
				$this->email->reply_to($input['email'], $input['name']);
				$this->email->message($email);
				$this->email->set_alt_message("No message available in plain text.");

				if ( ! $this->email->send())
				{
					log_message('error', "Impossibile inviare una mail di contatto. Contenuto del messagio: {$input['message']}");
					show_error('Invio email non riuscito, <a href="/admin.html">torna alla pagina principale</a> e riprova. Se il problema persiste <a href="mailto:tecnici@irislogin.it">contatta il supporto tecnico</a>.');
				}
				else
				{	
					if($from_uri)
						redirect($from_uri);
					else
						redirect("admin");
				}
			}
		}
	}
	
}


/* End of file error.php */
/* Location: ./application/controllers/util/error.php */