<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter extends ESO_Controller
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
			redirect("admin/login.html?dest=admin/newsletter");
			exit();
		}

		$this->view_data['user'] = $this->session->all_userdata();
		$this->user = $this->session->all_userdata();
		
		$this->load->model('newsletter_model');
		$this->load->model('users_model');
		// $this->load->model('offers_model', 'offers_db');
		
	}
		
	function index()
	{
		$this->view_data['draft_news'] = $this->newsletter_model->getMessageList($this->user['user_website'], array('status'=>'save'));
		if($this->view_data['draft_news']->total <= 0)
			unset($this->view_data['draft_news']);

		$this->view_data['sent_news'] = $this->newsletter_model->getMessageList($this->user['user_website'], array('status'=>'sent'));
		$this->view_data['subscribers'] = $this->newsletter_model->userGet($this->user['user_website']);
		
		$this->view_data['form_hidden'] = array('website_id'=>$this->user['user_website']);
		
		output(array(
				'header' => $this->head_data,
				'footer' => TRUE,
				'newsletter/admin_view' => $this->view_data
				));
	}
	
	
	function action($action, $n_id)
	{
		switch ($action) {
			case 'show':
				$this->_show_news($n_id);
				break;

			case 'edit':
				$this->_edit_news($n_id);
				break;

			case 'delete':
				$this->_delete_news($n_id);
				break;

			case 'test':
					$this->_send_test($n_id);
				break;

			default:
				show_404("admin/newsletter/{$n_id}/{$action}");
				break;
		}
	}
	
	function user($email, $action="show")
	{
		switch ($action) {
			case 'show':
				if($this->input->is_ajax_request())
				{
					$this->view_data['subscriber'] = $this->newsletter_model->userDetails($this->user['user_website'], $email);
					$this->load->view('newsletter/admin_user_view_ajax', $this->view_data);
				}
				else
					show_error("L'accesso diretto &egrave; negato.", 403);

				break;
			
			case 'delete':
			case 'remove':
				$result = $this->newsletter_model->userDel($this->user['user_website'], $email, TRUE);

				if($result)
					$this->view_data['message'] = "Utente {$email} rimosso correttamente dalla tue newsletter";
				else
					$this->view_data['error'] = "Errore nella rimozione dell'utente {$email}.";

				$this->index();
				break;
				
			case 'add':
				if($email == 'new')
				{
					show_404("admin/newsletter/user/{$email}/{$action}");
				}
				else
				{
					$result = $this->_add_user($this->user['user_website']);

					if(is_object($result))
					{
						$this->view_data['error'] = "Errore nell'inserimento del nuovo utente.";
						log_message('error', "[NEWSLETTER-{$this->user['user_website']}] Aggiunta Utente: {$result->error}");
					}
					else
						$this->view_data['message'] = "Utente aggiunto correttamente alla tua newsletter";

					$this->index();
					
				}
				break;
			
			case 'edit':
				// TODO
				// break;

			default:
				show_404("admin/newsletter/user/{$email}/{$action}");
				break;
		}
	}
	
	
	function create()
	{
		$data = $this->input->post();
		// Setting-up library "FormValidation"
		$form_config = array(
							array('field'   => 'subject',  'label'   => 'titolo della newsletter',  'rules'   => 'required|max_length[100]|min_length[5]'),
							array( 'field'   => 'from_email', 'label'   => ' email del mittente', 'rules'   => 'required|valid_email'),
							array('field'   => 'from_name',  'label'   => ' nome del mittente',  'rules'   => 'required'),
							array('field'   => 'to_name',  'label'   => ' nome dei destinatari',  'rules'   => 'required'),
							array('field'   => 'news_body', 'label'   => ' corpo della newsletter', 'rules'   => 'prep_for_form|required|min_length[50]'),
							);

		$this->form_validation->set_message('required', 'Il %s &egrave; obbligatorio.');
		$this->form_validation->set_message('max_length', 'Nel %s devi usare massimo %d caratteri.');
		$this->form_validation->set_message('min_length', 'Nel %s devi usare almeno %d caratteri.');
				
		$this->form_validation->set_rules($form_config);
		
		if($data != FALSE AND $this->form_validation->run())
		{
			$news_id = $this->newsletter_model->createMessage($data['website_id'], $data['subject'], $data['from_email'], $data['from_name'], $data['to_name'], array('html' => $data['news_body']));

			if($data['action'] == "Salva Bozza")
			{
				if($news_id !== FALSE)
					$this->view_data['message'] = "Messaggio salvato correttamente nel database. <br>"
					."Puoi gestire le bozze delle news nella sezione \"News non Inviate\"";
				else
					$this->view_data['error'] = "Il messaggio non &egrave; stato salvato correttamente in seguito ad un errore."
					."<br> Riprovare e se il problema persiste contattare l'assistenza.";

				$this->index();
			}
			elseif($data['action'] == "Invia Subito")
			{
				if($news_id !== FALSE)
				{
					$result = $this->newsletter_model->sendMessage($news_id);
					if($result == TRUE)
						$this->view_data['message'] = "Messaggio {$news_id} spedito correttamente.";
					else
						$this->view_data['error'] = "Il messaggio non &egrave; stato spedito correttamente in seguito ad un errore."
						."<br> È stato comunque eseguito un tentativo di salvare una bozza nel database che puoi gestire nella sezione \"News non Inviate\""
						."<br> Controllate se il salvataggio di emergenza &egrave; andato a buon fine ed incaso provate nuovamente ad inviarlo "
						."<br> Se il problema persista consigliamo di contattare l'assistenza.";
				}
				else
					$this->view_data['message'] = "Messaggio salvato correttamente nel database. <br>"
					."Puoi gestire le bozze delel news nella sezione \"News non Inviate\"";

				$this->index();

			}
			else
			{
				log_message('error', "Ricevuta Richiesta malformata in admin/newsletter/create.html: {$data['action']}");
				log_message('debug', print_r($data, TRUE));

				show_error("Azione Sconosciuta", 400);
			}
		}
		else
		{
			$this->view_data['form_send_hidden'] = array('website_id' => $this->user['user_website']);
			
			output(array(
					'header' => $this->head_data,
					'footer' => TRUE,
					'newsletter/admin_send_view' => $this->view_data
					));
		}
	}
	
	
	function manage($w_id = 1)
	{
		if($this->user['user_id']==USER_ID_MASTER_ADMIN)
		{
			$lists = false;
			// $lists = $this->newsletter_model->userAdd($w_id, "esolitos@gmail.com", "Marlon", "Esolitos"); //TESTED: OK
			// $lists = $this->newsletter_model->userDel($w_id, "esolitos@gmail.com", TRUE); //TESTED: OK
			// $lists = $this->newsletter_model->userGet($w_id); //TESTED: OK
			// $lists = $this->newsletter_model->listsGet(); //TESTED: OK
			
			$c_id = "dd7f48e0ae";
			$c_id_array = array("dd7f48e0ae", "6673e28a09", "2938bacb7f");
			
			$content['html'] =<<<E
<h1>Questo &grave; solo un test</h1>
<p>Se ricevi questo messaggio contatta <a href="mailto:marlon.saglia@gmail.com">Marlon Saglia</a> perché qualcosa &egrave; andato storto. Grazie</p>

<a href="mailto:esolitos@gmail.com?subject=provalink">Questo manda una mail.</a><br>

<b>Fine Prova</b>

 
E;
			
			// $lists = $this->newsletter_model->createMessage($w_id, "Altra prova", "esolitos@gmail.com", "Esolitos is Testing, again", "Eso Followers", array(), $content); //TESTED: OK
			// $lists = $this->newsletter_model->getMessageList($w_id); //TESTED: OK
			// $lists = $this->newsletter_model->getMessageList($w_id, array('campaign_id'=>$c_id)); //TESTED: OK
			// $lists = $this->newsletter_model->getMessageContent($c_id); //TESTED: OK
			// $lists = $this->newsletter_model->testMessage($w_id, $c_id, array("marlon@saglia.net", "jj.69kt@gmail.com")); //TESTED: OK
			
			
			
			
			print_r($lists);
			echo "\n\n";
			var_dump($lists);
			die;
			// $lists = $this->newsletter_model->getLists($w_id);
			
		}
		else
		{
			show_error('Accesso negato!', 403);
		}
	}
	
	
	function createList($w_id)
	{
		if($this->user['user_id']==USER_ID_MASTER_ADMIN)
		{
			$result = $this->newsletter_model->addWebsite($w_id);
			if($result===TRUE)
				$this->view_data['message'] = "Sito aggiunto correttamente!";
			else
				$this->view_data['error'] = "Sito non aggiunto! Servizio newsletter non ancora utilizzabile!";
			$this->index();
		}
		else
		{
			show_error('Accesso negato!', 403);
		}
	}



// Private Area



	function _show_news($n_id)
	{
		$data = $this->input->post();

		if($data != FALSE)
		{
			if($data['action'] == "Invia News")
			{
				$result = $this->newsletter_model->sendMessage($n_id);

				if($result == TRUE)
					$this->view_data['message'] = "Messaggio {$n_id} spedito correttamente.";
				else
					$this->view_data['error'] = "Il messaggio <em>{$n_id}</em> non &egrave; stato spedito correttamente in seguito ad un errore."
												."<br> Provate nuovamente ad inviarlo e nel caso in cui il problema persista consigliamo di contattare l'assistenza.";
			}
			else
			{
				show_error('Parametri errati!', 400);
			}
			
			$this->index();
		}
		elseif($this->input->is_ajax_request())
		{
			$this->view_data['form_hidden'] = array();
			$this->view_data['news_content'] = $this->newsletter_model->getMessageContent($n_id);
			$this->view_data['news'] = $this->newsletter_model->getMessageData($n_id);

			$this->view_data['edit'] = FALSE;

			$this->load->view('newsletter/admin_edit_view_ajax', $this->view_data);
		}
		else
		{
			redirect("admin/newsletter");
		}
	}

	// Tested
	private function _delete_news($n_id)
	{
		$result = $this->newsletter_model->removeMessage($n_id);
		if($result === TRUE)
		{
			$this->view_data['message'] = "Messaggio rimosso correttamente";
			$this->index();
		}
		else
		{
			$this->view_data['error'] = "Errore nella rimozione del messaggio: {$result->error}";
			$this->index();
		}
	}
	
	// Tested
	private function _edit_news($n_id)
	{
		if(($data = $this->input->post()) != FALSE)
		{
			$data['content']['html'] = $data['news_body_edit'];
			unset($data['news_body_edit']);
			
			$send_now = stristr($data['action'], "Invia");
			unset($data['action']);
			
			unset($data['website_id']);

			$result = $this->newsletter_model->updateMessage($n_id, $data);

			if($result == TRUE AND $send_now)
			{
				$result &= $this->newsletter_model->sendMessage($n_id);

				if($result == TRUE)
					$this->view_data['message'] = "Messaggio {$n_id} spedito correttamente.";
				else
					$this->view_data['error'] = "Il messaggio <em>{$n_id}</em> non &egrave; stato spedito correttamente in seguito ad un errore."
												."<br> Provate nuovamente ad inviarlo e nel caso in cui il problema persista consigliamo di contattare l'assistenza.";
			}
			else
			{
				if($result == TRUE)
					$this->view_data['message'] = "Messaggio {$n_id} aggiornato correttamente.";
				else
					$this->view_data['error'] = "Il messaggio <em>{$n_id}</em> non &egrave; stato aggiornato correttamente in seguito ad un errore."
												."<br> Riprovare e se il problema persiste contattare l'assistenza.";
			}
			
			$this->index();
		}
		else
		{
			$this->view_data['form_hidden'] = array('website_id'=>$this->user['user_website']);
			$this->view_data['news_content'] = $this->newsletter_model->getMessageContent($n_id);
			$this->view_data['news'] = $this->newsletter_model->getMessageData($n_id);

			$this->view_data['edit'] = TRUE;
			
			$this->load->view('newsletter/admin_edit_view_ajax', $this->view_data);
		}
	}


	private function _send_test($n_id)
	{
		$data['news_id'] = $n_id;
		
		if( $this->input->post('submit') != FALSE)
		{
			if(($email_list = $this->input->post('addresses')) != FALSE)
			{
				if(stristr($email_list, ','))
					$mails = explode(',', $email_list, NEWSLETTER_MAX_TEST_MAILS+1);
				else
					$mails = array($email_list);
				
				// Tronco l'array al numero massimo di indirizzi
				if(count($mails) > NEWSLETTER_MAX_TEST_MAILS)
					$mails = array_slice($mails, 0, NEWSLETTER_MAX_TEST_MAILS);

				$result = $this->newsletter_model->testMessage($this->user['user_website'], $n_id, $mails);
				
				if($result == TRUE AND ! is_object($result))
				{
					$data['message'] = "Test Spediti correttamente agli indirizzi:".implode(", ", $mails);
				}
				elseif(is_object($result))
				{
					switch ($result->code) {
						case 120:
							$data['error'] = "Hai superato la quota massima di test disponibili per una singola newsletter."
											."<br/><a href=\"mailto:supporto@irislogin.it?subject=Overuota Test Newsletter\">Contattaci</a> per maggiori informazioni.";						
							log_message('info', "NEWSLETTER - Utente Over-Quota per la campagna: $n_id");
							break;
						case 'overquota':
							$data['error'] = "Hai superato la quota giornaliera di test disponibili. "
											."<br/>Puoi inviare solo ".NEWSLETTER_MAX_TEST." test al giorno."
											."<br/><a href=\"mailto:supporto@irislogin.it?subject=Overuota Test Newsletter\">Contattaci</a> per maggiori informazioni.";
							break;
						
						case 'not-configured':
							$data['error'] = "Siamo spiacenti ma il servizio non sembra configurato correttamente."
											."<br/>Riprovare nelle prossime 24h e se il problema persiste contattateci.";
							log_message('error', "NEWSLETTER - Servizio non Configurato per il sito: {$this->user['user_website']}!");
							break;
						
						case 502:
							$data['error'] = "Indirizzi eMail non inseriti correttamente. Controlla i dati inseriti e riprova.";
							log_message('info', "NEWSLETTER - Indirizzo email non corretto nell'invio del test. (".$result->error.")");
							break;
						
						default:
							$data['error'] = "Errore nell'invio dei test. Controlla i dati inseriti e riprova.";
							log_message('error', "NEWSLETTER - Errore nell'invio del test. \n\tCode:".$result->code."\n\tError:".$result->error);
							break;
					}
				}
				else
				{
					log_message('error', "NEWSLETTER - Invio test non riuscito:".print_r($result, TRUE));
					$data['error'] = "Test <strong>non inviati correttamente</strong>. Riprovare e se il problema persiste contattare un amministratore";
				}
				
				$this->load->view("newsletter/admin_test_modal_view", $data);
			}
			else
			{
				$data = array(
					'news_id' => $n_id,
					'error' => "Devi indicare almeno un indirizzo a cui inviare il messaggio di test!"
					);
				$this->load->view("newsletter/admin_test_modal_view", $data);
			}
		}
		else if($this->input->is_ajax_request())
		{
			$this->load->view("newsletter/admin_test_modal_view", $data);
		}
		else
		{
			show_error("L'accesso diretto &egrave; negato.", 403);
		}
	}

	private function _add_user($website_id)
	{
		$this->form_validation->set_message('required', '%s');
		
		$form_config = array(
							array(
								'field'   => 'user_email', 
								'label'   => 'L\'email dell\'utente &egrave; obbligatoria.', 
								'rules'   => 'trim|required|valid_email'
								),
							array(
								'field'   => 'user_name',
								'label'   => 'Devi inserire un nome per il tuo utente.',
								'rules'   => 'trim|required'
								),
							array(
								'field'   => 'user_surname',
								'label'   => '',
								'rules'   => ''
								)
							);
		
		
		$this->form_validation->set_rules($form_config);

		if($this->form_validation->run() === FALSE)
		{
			$this->index();
		}
		else
		{
			$email = $this->input->post('user_email');
			$name = $this->input->post('user_name');
			$surname = $this->input->post('user_surname');

			return $this->newsletter_model->userAdd($website_id, $email, $name, $surname);
		}
		
	}

}


/* End of file websites.php */
/* Location: ./application/controllers/admin/websites.php */