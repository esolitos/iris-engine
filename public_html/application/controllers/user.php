<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends ESO_Controller
{
	var $view_data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();
		
		$this->load->model('users_model', 'users');
	}
	
	function index()
	{
		show_error("Accesso negato.", '403');
	}
	
	function password($action='lost', $u_id=FALSE)
	{	
		switch ($action) {
			case 'lost':
				$this->_lostPWD();
				break;
			
			case 'reset':
				$this->_resetPWD($u_id);
				break;
			
			case 'update':
				$this->_updatePWD($u_id);
				break;
			
			default:
				show_404();
				break;
		}
	}
	
	
	
	// ----------------- Private
	
	private function _lostPWD()
	{
		if($this->input->post('submit') != FALSE)
		{	
			$this->form_validation->set_message('required', '%s obbligatorio.');
			$this->form_validation->set_message('in_database', 'Devi indicare un %s presente nel sistema.');

			$form_rules = array(
				array(
					'field' => 'username', 
					'label' => 'Nome Utente', 
					'rules' => 'trim|required|in_database['.TABLE_USERS.'.username]'
					),
				array(
					'field' => 'email', 
					'label' => 'eMail', 
					'rules' => 'trim|required|valid_email|in_database['.TABLE_USERS.'.email]'
					)
				);

			$this->form_validation->set_rules($form_rules);

			if( ! $this->form_validation->run())
			{
				output(array(
						'header' => init_headdata(),
						'footer' => TRUE,
						'users/password_recovery_view' => $this->view_data
						));
			}
			else
			{
				if( ($user_data = $this->users->load_user_data($this->input->post('username'))) === FALSE)
				{
					$this->view_data['error'] = "L'utente indicato non è presente nel sistema. Prego ricontrollare i dati inseriti.";
				}
				elseif($user_data['info']->id == USER_ID_MASTER_ADMIN)
				{
					log_message('ERROR', 'Tentativo di reset della passowrd di amministrazione. Azione Bloccata.');
					$this->view_data['error'] = "<strong>ATTENZIONE</strong>: Non puoi resettare la password dell'amministratore.<br> Tentativo di intrusione registrato.";
				}
				elseif($user_data['info']->email != $this->input->post('email'))
				{
					$this->view_data['error'] = "L'indirizzo email inserito non corrisponde a quello di registrazione";
				}
				else
				{
					if($this->users->update_pwd($user_data['info']->id))
					{
						$this->load->library('email');
						
						$this->email->to($user_data['info']->email);
						$this->email->bcc(IRIS_MAIL); 
						$this->email->from(IRIS_MAIL_TECH, 'IrisLogin');
						$this->email->subject("Nuova password per IRISLogin");
						$this->email->message("<h1>Nuova Password di Accesso</h1>".
											"<p>La tua nuova passowrd di accesso al sistema è: <code>".DEFAUT_PASSWORD."</code></p>".
											"<br><br><br><p>Se non hai richiesto tu questa azione <a href=\"mailto:".IRIS_MAIL_TECH."\">contattaci al piu presto</a> e comunicaci questo problema! Ci scusiamo per l'inconveniente."
											);

						if($this->email->send())
							$this->view_data['message'] = 'Password reimpostata correttamente. Controlla nella tua casella di posta per conoscere la tua nuova passowrd.<br><a href="/admin.html">&larr; Torna alla home</a>';
						else
						{
							$this->view_data['message'] = 'Password reimpostata correttamente.<br><a href="/admin.html">&larr; Torna alla home</a>';
							$this->view_data['error'] = 'Impossibile inviare la mail contenete la tua nuova passowrd. <a href="mailto:'.IRIS_MAIL_TECH.'">Contatta i nostri tecnici</a> che risolveranno il problema in un istante. Ci scusiamo per il disagio.';
						}
					}
					else
					{
						$this->view_data['error'] = 'Impossibile reimpostare la passowrd. <a href="mailto:'.IRIS_MAIL_TECH.'">Contatta i nostri tecnici</a> e comunica loro questo fatto!. <br> Ci scusiamo per l\'inconveniente.';
						log_message('ERROR', "Impossibile modificare la password dell'utente {$user_data['info']->username}");
					}	
				}
				output(array(
						'header' => init_headdata(),
						'footer' => TRUE,
						'users/password_recovery_view' => $this->view_data
						));
			}
		}
		else
		{
			output(array(
					'header' => init_headdata(),
					'footer' => TRUE,
					'users/password_recovery_view' => $this->view_data
					));
		}
	}

	private function _updatePWD($user_id = FALSE)
	{
		if( ! $this->session->userdata('logged_in'))
		{
			redirect("admin/login.html?dest=user/password/update");
			exit();
		}

		$this->view_data['user'] = $this->session->all_userdata();
		$this->view_data['update_success'] = FALSE;

		if($user_id === FALSE)
			$user_id = $this->session->userdata('user_id');
	
		if( $this->input->post('submit') !== FALSE )
		{
			$this->form_validation->set_message('required', '%s obbligatorio.');
			$this->form_validation->set_message('matches', 'Le nuove password non corrispondono.');
			$this->form_validation->set_message('min_length', '%s deve essere di almeno %d caratteri.');
			$this->form_validation->set_message('in_database', 'Devi indicare un %s presente nel sistema.');

			$form_rules = array(
				array(
					'field' => 'old_password', 
					'label' => 'Vecchia Password', 
					'rules' => 'trim|required'
				),
				array(
					'field' => 'new_password', 
					'label' => 'Nuova Password', 
					'rules' => 'trim|required'
					// 'rules' => 'trim|required|min_length[6]'
				),
				array(
					'field' => 'new_password_check', 
					'label' => 'Password di Controllo', 
					'rules' => 'trim|required|matches[new_password]'
					),
				array(
					'field' => 'email', 
					'label' => 'Indirizzo Email', 
					'rules' => 'trim|required|in_database['.TABLE_USERS.'.email]'
					)
				);

			$this->form_validation->set_rules($form_rules);

			if( ! $this->form_validation->run())
			{
				output(array(
						'header' => init_headdata(),
						'footer' => TRUE,
						'users/password_update_view' => $this->view_data
						));
				return TRUE;
			}
			else
			{
				if( ($user_data = $this->users->load_user_data($user_id)) === FALSE)
				{
					$this->view_data['error'] = "L'utente indicato non è presente nel sistema. Prego ricontrollare i dati inseriti.";
				}
				elseif($user_data['info']->email != $this->input->post('email'))
				{
					$this->view_data['error'] = "L'indirizzo email inserito non corrisponde a quello indicato in fase di registrazione.";
				}
				elseif( $this->users->login($user_data['info']->username, $this->input->post('old_password'), TRUE) == FALSE)
				{
					$this->view_data['error'] = "La vecchia password non è corretta. Inserisci nuovametne i dati e riprova.";
				}
				else
				{
					if( $this->users->update_pwd($user_id, $this->input->post('new_password')) )
					{
						$this->view_data['update_success'] = TRUE;
						$this->view_data['message'] = "La tua password è stata aggiornata con successo. \n<a class=\"btn btn-success\" href=\"/admin\">&larr; Torna alla pagina di amministrazione.</a>";
					}
				}	
			}
		}
		
		output(array(
				'header' => init_headdata(),
				'footer' => TRUE,
				'users/password_update_view' => $this->view_data
				));
	}
}


/* End of file login.php */
/* Location: ./application/controllers/admin/login.php */