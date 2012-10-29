<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter_Model extends CI_Model
{
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


	function userAdd($w_id, $email, $name, $surname)
	{
		$result = FALSE;
		$this->db->select('newsletter_key as `key`');
		$n_id = $this->db->get_where(TABLE_NEWSLETTER, array('website_id'=>$w_id), 1, 0)->row()->key;
		
		if($n_id != NULL)
		{
			$data = array(
						'apikey'		=> MAILCHIMP_API_KEY,
						'id'			=> $n_id,
						'email_address'	=> $email,
						'merge_vars'	=> array(),
					);
			
			if($name != NULL)
				$data['merge_vars']['FNAME'] = $name;
			
			if($surname != NULL)
				$data['merge_vars']['LNAME'] = $surname;
			
			
			try {
				$result = post_as_json(MAILCHIMP_URL.'listSubscribe', $data);
				
			} catch (Exception $e) {
				log_message('error', 'NewsletterModel.userAdd(): Request failed!');
			}
		}
		
		return $result;
	}


	function userDel($w_id, $email, $goodbye = FALSE)
	{
		$result = FALSE;
		$this->db->select('newsletter_key as `key`');
		$n_id = $this->db->get_where(TABLE_NEWSLETTER, array('website_id'=>$w_id), 1, 0)->row()->key;
		
		if($n_id != NULL)
		{
			$data = array(
						'apikey'		=> MAILCHIMP_API_KEY,
						'id'			=> $n_id,
						'email_address'	=> $email,
						'send_goodbye'	=> $goodbye
					);
					
			try {
				$result = post_as_json(MAILCHIMP_URL.'listUnsubscribe', $data);

			} catch (Exception $e) {
				log_message('error', 'NewsletterModel.userDel(): Request failed!');
			}
		}
		
		return $result;
	}


	function userGet($w_id)
	{
		$result = FALSE;
		$this->db->select('newsletter_key as `key`');
		$n_id = $this->db->get_where(TABLE_NEWSLETTER, array('website_id'=>$w_id), 1, 0)->row()->key;
		
		if($n_id != NULL)
		{
			$data = array(
						'apikey'		=> MAILCHIMP_API_KEY,
						'id'			=> $n_id,
						'status'		=> 'subscribed'
					);

			try {
				//listMembers(string apikey, string id, string status, string since, int start, int limit)
				$result = post_as_json(MAILCHIMP_URL.'listMembers', $data);

			} catch (Exception $e) {
				log_message('error', 'NewsletterModel.userGet(): Request failed!');
			}
		}

		return $result->data;
	}
	

	function userDetails($w_id, $email)
	{
		$result = FALSE;
		$this->db->select('newsletter_key as `key`');
		$n_id = $this->db->get_where(TABLE_NEWSLETTER, array('website_id'=>$w_id), 1, 0)->row()->key;
		
		if($n_id != NULL)
		{
			$data = array(
						'apikey'		=> MAILCHIMP_API_KEY,
						'id'			=> $n_id,
						'email_address'	=> $email,
					);

			try {
				// listMemberInfo(string apikey, string id, array email_address)
				$result = post_as_json(MAILCHIMP_URL.'listMemberInfo', $data);

			} catch (Exception $e) {
				log_message('error', 'NewsletterModel.userGet(): Request failed!');
			}
		}

		return $result->data[0];
			
	}

	
	function listsGet()
	{
		$result = FALSE;
		$data = array(
			'apikey'		=> MAILCHIMP_API_KEY,
			);

		try {
			//listMembers(string apikey, string id, string status, string since, int start, int limit)
			$result = post_as_json(MAILCHIMP_URL.'lists', $data);

		} catch (Exception $e) {
			log_message('error', 'NewsletterModel.listsGet(): Request failed!');
		}

		return $result;		
	}
	
	
	function getMessageList($w_id, $options=array())
	{
		$result = FALSE;
		$allowed_options = array('campaign_id', 'template_id', 'content_type', 'title', 'type', 'status', 'from_email');
		
		$this->db->select('newsletter_key AS `key`');
		$query = $this->db->get_where(TABLE_NEWSLETTER, array('website_id'=>$w_id), 1);
		
		if($query->num_rows() > 0 AND $query->row()->key)
		{
			$data = array(
				'apikey'		=> MAILCHIMP_API_KEY,
				'filters'		=> array(
										'list_id'	=> $query->row()->key,
									),
				);
		
			foreach($options as $key=>$value)
			{
				if(in_array($key, $allowed_options))
					$data['filters'][$key] = $value;
			}
		
			try {
				// campaigns(string apikey, array filters, int start, int limit)
				$result = post_as_json(MAILCHIMP_URL.'campaigns', $data);
				
				if(isset($result->total) AND $result->total > 0)
				{
					foreach ($result->data as $k=>$value) {
						if(stripos($value->subject, "Nuova offerta su") !== FALSE)
						{
							unset($result->data[$k]);
							$result->total--;
						}
					}
					
					return $result;
				}

			} catch (Exception $e) {
				log_message('error', 'NewsletterModel.getMessageList(): Request failed!');
			}
		}
		
		$result = new stdClass();
		$result->total = 0;
		$result->data = array();
		log_message('error', 'NewsletterModel.getMessageList(): Service not configured for website '.$w_id);
		
		return $result;
	}
	
	
	function createMessage($w_id, $subject, $from_email, $from_name, $to_name, $content,  $options=array())
	{
		$result = FALSE;
		
		$this->db->select('newsletter_key as `key`');
		$query = $this->db->get_where(TABLE_NEWSLETTER, array('website_id'=>$w_id), 1, 0);
		
		if($query->num_rows() > 0 AND $query->row()->key)
		{
			$footer =<<<FOOT
				<div style="display:block;margin-top:25px;background-color:#EEE;font-size:smaller;border-top:1px solid #AAA;padding:10px;">
				This email comes to you from: *|LIST:DESCRIPTION|* <br />
				<a href="*|UPDATE_PROFILE|*">Update your profile</a> - <a href="*|UNSUB|*">Unsubscribe</a> *|EMAIL|* from this list.<br />
				<br />
				Our mailing address is: <div style="dispolay:inline-block;white-space:nowrap;">*|LIST:ADDRESS|*</div><br />
				<div style="text-align:right;">
				 *|REWARDS|*
				</div>
				</div>
FOOT;
			$content['html'] .= $footer;
			$data = array(
						'apikey'		=> MAILCHIMP_API_KEY,
						'type'			=> 'regular',
						'options'		=> array(
											'list_id'	=>	$query->row()->key,
											'subject'	=> $subject,
											'from_email'	=> $from_email,
											'from_name'		=> $from_name,
											'to_name'		=> $to_name,
											'generate_text'	=> TRUE,
										),
						'content'		=> $content,
					);

			foreach ($options as $name => $value)
				$data['options'][$name] = $value;
					
			try {
				//listMembers(string apikey, string id, string status, string since, int start, int limit)
				$result = post_as_json(MAILCHIMP_URL.'campaignCreate', $data);
				
				if(is_object($result))
				{
					log_message('error', "NewsletterModel.createMessage(): ".print_r($result, TRUE));
					return FALSE;
				}

			} catch (Exception $e) {
				log_message('error', 'NewsletterModel.createMessage(): Request failed!');
			}
		}
		else
		{
			$result = new stdClass();
			$result->code = 'not-configured';
			$result->error = "Il servizio di Newsletter non è configurato corerttamente";
			log_message('error', 'NewsletterModel.createMessage(): Service not configured for website '.$w_id);
		}

		
		return $result;
	}


	function getMessageContent($c_id)
	{
		$result = FALSE;

		$data = array(
			'apikey'		=> MAILCHIMP_API_KEY,
			'cid'			=> $c_id,
			'for_archive'	=> FALSE,
			);


		try {
			//campaignContent(string apikey, string cid, bool for_archive)
			$result = post_as_json(MAILCHIMP_URL.'campaignContent', $data);

		} catch (Exception $e) {
			log_message('error', 'NewsletterModel.getMessageData(): Request failed!');
		}

		return $result;
	}


	function updateMessage($c_id, $in_data)
	{
		$result = FALSE;

		$data = array(
			'apikey'		=> MAILCHIMP_API_KEY,
			'cid'			=> $c_id,
			);

		foreach ($in_data as $name => $value) {
			$data['name'] = $name;
			$data['value'] = $value;

			try {
				// campaignUpdate(string apikey, string cid, string name, mixed value)
				$result = post_as_json(MAILCHIMP_URL.'campaignUpdate', $data);

				if(isset($result->error))
				{
					log_message('error', "NewsletterModel.updateMessage(): Server returned '{$result->error}'");
					return FALSE;
				}

			} catch (Exception $e) {
				log_message('error', 'NewsletterModel.updateMessage(): Request failed!');
				return FALSE;
			}
		}


		return $result;
	}


	function getMessageData($c_id)
	{
		$result = FALSE;
		
		if($c_id != NULL)
		{
			$data = array(
						'apikey'		=> MAILCHIMP_API_KEY,
						'filters'		=> array('campaign_id' => $c_id),
					);
	
			try {
				$result = post_as_json(MAILCHIMP_URL.'campaigns', $data);
				
				if($result->total !== 1)
				{
					log_message('debug', "NewsletterModel.getMessageData(): Expecting 1 campaign and server responded: {$result->total}!");
					$result = FALSE;
				}
				else
				{
					$result = $result->data[0];
				}
			} catch (Exception $e) {
				log_message('error', 'NewsletterModel.getMessageData(): Request failed!');
			}
		}
		
		return $result;
	}
	
	
	function removeMessage($c_id)
	{
		$result = FALSE;
		
		$data = array(
					'apikey'		=> MAILCHIMP_API_KEY,
					'cid'			=> $c_id,
				);

				
		try {
			//campaignDelete(string apikey, string cid)
			$result = post_as_json(MAILCHIMP_URL.'campaignDelete', $data);

		} catch (Exception $e) {
			log_message('error', 'NewsletterModel.getMessageData(): Request failed!');
		}
		
		return $result;
	}


	function sendMessage($c_id)
	{
		$result = FALSE;
		
		$data = array(
					'apikey'		=> MAILCHIMP_API_KEY,
					'cid'			=> $c_id,
				);

		try {
			// campaignSendNow(string apikey, string cid)
			$result = post_as_json(MAILCHIMP_URL.'campaignSendNow', $data);

		} catch (Exception $e) {
			log_message('error', 'NewsletterModel.getMessageData(): Request failed!');
		}
		
		return $result;
	}


	function testMessage($w_id, $msg_id, $addresses)
	{
		$result = FALSE;
		
		if( ! is_array($addresses))
			return $result;

		$this->db->select('newsletter_key as `key`, last_test, num_tests');
		$query = $this->db->get_where(TABLE_NEWSLETTER, array('website_id'=>$w_id), 1, 0);

		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$last_test = time() - strtotime($row->last_test);
			if(
				 $last_test > H24_TIMESTAMP // Ultimo test inviato più di 24h fa
				OR ($last_test < H24_TIMESTAMP AND $row->num_tests < NEWSLETTER_MAX_TEST ) // *OPPURE* Ultimo test inviato nelle ultime 24h (24h * 60' * 60") *AND* limite giornaliero di test non superato
				)
			{
				$data = array(
					'apikey'		=> MAILCHIMP_API_KEY,
					'cid'			=> $msg_id,
					'test_emails'	=> $addresses,
					);


				try {
					//campaignSendTest(string apikey, string cid, array test_emails, string send_type)
					$result = post_as_json(MAILCHIMP_URL.'campaignSendTest', $data);
					
					if( ! is_object($result)) //Aggiorno il db solo in caso di invio riuscito.
					{
						$update['num_tests'] = ($last_test > H24_TIMESTAMP) ? 1 : ++$row->num_tests ;
						$this->db->update(TABLE_NEWSLETTER, $update, array('website_id'=>$w_id));
					}
					
				} catch (Exception $e) {
					log_message('error', 'NewsletterModel.getMessageData(): Request failed!');
				}
			}
			else
			{
				$result = new stdClass();
				$result->code = 'overquota';
				$result->error = "Impossibile inviare pi&ugrave; di ".NEWSLETTER_MAX_TEST." test in meno di 24 ore";
				log_message('info', 'NewsletterModel.testMessage(): Too many test request for website '.$w_id);
			}
			
		}
		else
		{
			$result = new stdClass();
			$result->code = 'not-configured';
			$result->error = "Il servizio di Newsletter non è configurato corerttamente";
			log_message('error', 'NewsletterModel.testMessage(): Service not configured for website '.$w_id);
		}
		
		return $result;
	}	

	function test()
	{
		$result = FALSE;
		try {
			$result = post_as_json(MAILCHIMP_URL.'ping', array('apikey'=>MAILCHIMP_API_KEY));
		} catch (Exception $e) {
			log_message('error', 'MailChimp: Request failed!\nService offline!!!');
		}
		
		return $result;
	}
	
	
}