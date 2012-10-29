<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offers extends CI_Controller
{
	var $view_data=array();
	var $userdata=array();
	var $form_config = array();

	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();
		
		if( ! $this->session->userdata('logged_in'))
		{
			redirect("admin/login.html?dest=admin/offers");
			exit();
		}

		$this->load->model('offers_model');
		$this->load->model('settings_model');
		
		$this->userdata = $this->session->all_userdata();
		$this->view_data['user'] = $this->userdata;
		
		$this->form_validation->set_message('required', 'Il %s &egrave; obbligatorio.');
		$this->form_validation->set_message('_check_date', 'Devi inserire una data corretta!');
		$this->form_validation->set_message('max_length', 'Nel %s devi usare massimo %d caratteri.');
		$this->form_validation->set_message('min_length', 'Nel %s devi usare almeno %d caratteri.');
		
		// Setting-up library "FormValidation"
		$this->form_config = array(
							array(
								'field'   => 'offer_title', 
								'label'   => 'titolo del\'offerta', 
								'rules'   => 'required|max_length[50]|min_length[3]'
								),
							array(
								'field'   => 'offer_body', 
								'label'   => 'testo dell\'offerta', 
								'rules'   => 'required'
								),
							array(
								'field'   => 'offer_expire', 
								'label'   => 'la data di scadenza', 
								'rules'   => 'callback_check_date_it'
								),
							array(
								'field'   => 'expires',
								'label'   => '',
								'rules'   => ''
								),
							array(
								'field'   => 'offer_special',
								'label'   => '',
								'rules'   => ''
								)
							);
	}
	
	function index()
	{
		$this->view_data['form_add_attrib'] = array();
		$this->view_data['form_add_hidden'] = array(
											'author_id' => $this->userdata['user_id'],
											'user_website' => $this->userdata['user_website']
											);

		$offers = $this->offers_model->get_offers($this->userdata['user_website']);

		if($offers['status'] === TRUE)
			$this->view_data['offers'] = $offers['result'];
		else
			$this->view_data['exec_result'] = $offers['errors'];
		
		$this->view_data['custom_style'] = $this->settings_model->getOptionArray($this->userdata['user_website'], SERVICE_ID_OFFERS, array('text_color', 'title_color', 'bg_color'));

		if(($style = $this->websites_model->get_style($this->userdata['user_website'], SERVICE_ID_OFFERS)) != FALSE)
			$this->view_data['css_file'] = $style;
		
		// $this->load->view('admin/offers_view', $this->view_data);
		output(array(
				'header' => init_headdata(),
				'footer' => TRUE,
				'offers/admin_view' => $this->view_data
				));
	}
	
	function add()
	{
		$image_data=FALSE;
		$with_image = FALSE;
		
		// init the $_POST input array
		$input = $this->input->post();
		
		// ----------------------------------------------------------------------------
		// -----         Setting up and Loading needed Libraryes/Classes          -----
		// ----------------------------------------------------------------------------
		$this->form_validation->set_rules($this->form_config);
		// ----------------------------------------------------------------------------
		// -----                  End of settings and loading                     -----
		// ----------------------------------------------------------------------------
		
		
		if($this->form_validation->run() === FALSE)
		{			
			$this->view_data['form_add_hidden'] = array(
												'author_id' => $this->userdata['user_id'],
												'user_website' => $this->userdata['user_website']
												);

			output(array(
					'header' => init_headdata(),
					'footer' => TRUE,
					'offers/admin_view' => $this->view_data
					));
		}
		else // The form was submitted correctly
		{
			// Uploads the image
			if($_FILES['offer_image']['name'] != NULL)
			{
				$input['offer_image'] = '';
				$image_upload = $this->_upload_image('offer_image');

				if($image_upload['status'])
					$input['offer_image'] = $image_upload['info']['file_name'];
				else
					$this->view_data['error'] .= "{$image_upload['errors']}<br/>";
			}
			
			if($this->view_data['error'])
			{
				$this->index();
			}
			else
			{				
				$add_wid = $input['user_website'];
				unset($input['submit']);
				unset($input['send_message']);
				unset($input['user_website']);
				
				$res = $this->offers_model->add_offer($input, $add_wid);
				if( ! $res['status'])
				{
					$this->view_data['error'] .= "{$res}";
					$this->index();
				}
				else
				{
					if($this->input->post('send_message'))
						$this->_tell_people($res['id']);

					redirect("admin/offers/index/{$res['id']}/added");
				}
			}
		}
	}
	
	function edit($action)
	{
			// $action = $this->uri->segment(4);
			if(is_numeric($action))
			{
				
				$offer_data = $this->offers_model->load_offer($action);
				if($offer_data['status'] === TRUE)
				{
					$this->view_data['offer'] = $offer_data['result'];
					$this->view_data['form_edit_hidden'] = array('id' => $offer_data['result']->offer_id);
					
					output(array(
							'header' => init_headdata(),
							'footer' => TRUE,
							'offers/admin_edit_view' => $this->view_data
							));
				}
				else
				{
					$this->view_data['exec_result'] = $offer_data['error'];					
					$this->index();
				}
			}
			else if($action === 'save')
			{
				$input = $this->input->post();
				$oid = $input['id'];
					
				unset($input['id']);
				unset($input['submit']);
			
			
				// ----------------------------------------------------------------------------
				// -----         Setting up and Loading needed Libraryes/Classes          -----
				// ----------------------------------------------------------------------------
				$this->load->helper('form');
				$this->form_validation->set_rules($this->form_config);
				// ----------------------------------------------------------------------------
				// -----                  End of settings and loading                     -----
				// ----------------------------------------------------------------------------
				
				
				if($this->form_validation->run() === FALSE)
				{			
					$this->edit($oid);
				}
				else
				{
					if($_FILES['offer_image']['name'] != NULL)
					{
						$upload = $this->_upload_image('offer_image');
						if($upload['status'])
						{
							$input['offer_image'] = $upload['info']['file_name'];
							$res = $this->offers_model->edit_offer($oid, $input);

							if($res['status'])
								$this->view_data['exec_result'] = "The offer \"{$input['offer_title']}\" has been updated!";
							else
								$this->view_data['exec_result'] = "Unable to edit offer: ".$res['error'];
						}
						else
							$this->view_data['exec_result'] = "Unable to edit offer: ".$upload['errors'];
					}
					else
					{
						$res = $this->offers_model->edit_offer($oid, $input);

						if($res['status'])
							$this->view_data['exec_result'] = "The offer \"{$input['offer_title']}\" has been updated!";
						else
							$this->view_data['exec_result'] = "Unable to edit offer: ".$res['error'];
					}

					$this->index();	
				}
			}
			else
			{
				redirect("admin/offers");
			}
	}
	
	function hide()
	{
		$o_id = $this->uri->segment(4);
		
		if(is_numeric($o_id))
			$this->_change_visibility($o_id, 0);
		else
			redirect("admin/offers");
	}
	
	function show()
	{
		$o_id = $this->uri->segment(4);
		
		if(is_numeric($o_id))
			$this->_change_visibility($o_id, 1);
		else
			redirect("admin/offers");
	}
	
	function delete()
	{
		if($this->uri->segment(4)===FALSE)
			redirect("admin/offers");
		else
		{
			$res = $this->offers_model->remove_offer($this->uri->segment(4));
			if( ! $res['status'])
				$this->view_data['exec_result'] = $res['error'];
				
			else
				$this->view_data['exec_result'] = "Succesfully removed {$res['result']} offer(s).";

			$this->index();
		}
	}

	
	// Private functions
	
	private function _upload_image($image_field)
	{
		// Loading library "Upload" for the image upload
		$upload_config['upload_path'] = PATH_SRV_UPLOAD;
		$upload_config['allowed_types'] = 'jpg|png|gif';
		$upload_config['max_size'] = '500';			// size in KB
		$upload_config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $upload_config);
		
		// Loading library "Image Lib" for the image resizing
		// Remember to set the parameter 'source_image'!!
		$image_config['quality'] = '75%';
		$image_config['width'] = 300;
		$image_config['height'] = 1000;
		$image_config['master_dim'] = 'width';
		$image_config['maintain_ratio'] = TRUE;
		
		$this->load->library('image_lib');
		
		
		if($this->upload->do_upload($image_field))
		{	
			$image_data = $this->upload->data();
		
			$image_config['source_image'] = $image_data['full_path'];
			$this->image_lib->initialize($image_config);
		
			$this->image_lib->resize();
			
			return array('status' => TRUE, 'info' => $image_data);
		}
		else
			return array('status' => FALSE, 'errors' => $this->upload->display_errors());

	}
	
	private function _change_visibility($o_id, $visibility)
	{
		$res = $this->offers_model->edit_offer($o_id, array('offer_visible' => $visibility));
		if($res['status'])
			$this->view_data['exec_result'] = "The offer is now visible to the public!";
		else
			$this->view_data['exec_result'] = "I wasn't able to change visibility of the offer. ".$res['error'];
		
		$this->index();
	}

	private function _tell_people($offer_id)
	{
		// Check Newsletter Subscription status
		$website_id = $this->session->userdata('user_website');
		
		if(subscription_status($website_id, SERVICE_NAME_NEWSLETTER))
		{	
			// Load Website Data
			$w_data = $this->websites_model->load_website_data($website_id);
			$mail_view_data['website'] = $w_data = $w_data['info'];
			// Load Offer Data
			$mail_view_data['offer_url'] = base_url("offers/view/{$offer_id}?return=$w_data->website_url");
			$mail_view_data['odder_id'] = $offer_id;
			
			// Create Newsletter Campaign
			$this->load->model('newsletter_model');
			$news_subject = "Nuova offerta su {$w_data->website_name}!";
			$news_to = "";
			$news_body = $this->load->view('mail/offer_newsletter_view', $mail_view_data, TRUE);
			
			$news_id = $this->newsletter_model->createMessage($website_id, $news_subject, $w_data->website_email, $w_data->website_name, $news_to, array('html' => $news_body));

			// Send Campaign
			if($news_id !== FALSE)
				return $this->newsletter_model->sendMessage($news_id);
		}

		return FALSE;
	}

}


/* End of file offers.php */
/* Location: ./application/controllers/admin/offers.php */