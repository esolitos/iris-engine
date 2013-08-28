<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Offers_Model extends CI_Model
{
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();

    }
	
	// Returns an array of offers, NULL with no results and FALSE with error.
	function get_offers($website=NULL, $lang=LANG_DEFAULT, $lang_strict = FALSE)
	{
		try
		{
			$this->db->distinct()->select("id, username AS author, DATE_FORMAT(offer_creation, '%d-%m-%Y %H:%i') as offer_creation, offer_last_edit, DATE_FORMAT(offer_expire, '%d-%m-%Y') as offer_expire, offer_special, offer_image, offer_visible, , website_name", FALSE);
			$this->db->from(TABLE_OFFERS)
					->join(TABLE_USERS, 'author_id = user_id')
						->join(TABLE_WEBSITES, TABLE_OFFERS.".website_id=".TABLE_WEBSITES.".website_id");
				
			if($website)
				$this->db->where(TABLE_OFFERS.".website_id", $website);
			
			$this->db->order_by('offer_creation', 'ASC');

			$results=array();
			$db_data = $this->db->get()->result();
			foreach($db_data as $row)
			{
				if($row->offer_expire == NULL)
					$row->expired = FALSE;
				else
					$row->expired = $this->_is_expired($row->offer_expire);
				
				if($this->_getTranslations($row, $lang, $lang_strict))
					$results[]=$row;
			}
			return array('status' => TRUE, 'result' => $results);

		}
		catch (Exception $e)
		{
			return array('status' => FALSE, 'error' => $e->getMessage());
		}
	}
	
	
	function get_offers_titles($website=NULL, $limit = FALSE, $lang=LANG_DEFAULT, $lang_strict = FALSE)
	{
		try
		{	
			$this->db->select("id, offer_special")
				->from(TABLE_OFFERS)
					->where('offer_visible', 1)
						->where('( offer_expire > "'. date("Y-m-d") . '" OR offer_expire IS NULL )')
							->order_by('offer_special', 'DESC')
								->order_by('offer_creation', 'DESC');
			
			if($website != NULL)
				$this->db->where(TABLE_OFFERS.".website_id", $website);
			
			if( $limit )
				$this->db->limit($limit);
			
			$db_data = $this->db->get()->result();
			foreach($db_data as $row)
				if($this->_getTranslations($row, $lang, $lang_strict))
					$results[]=$row;
				
			return array('status' => TRUE, 'result' => $results);

		}
		catch (Exception $e)
		{
			return array('status' => FALSE, 'error' => $e->getMessage());
		}
	}
	
	
	// Should return the new offer_id or a string containing the error
	function add_offer($input_data, $website_id=FALSE)
	{
		if( empty($input_data['offer_expire']))
		{
			$input_data['offer_expire']=NULL;
		}
		else
		{
			$date = $input_data['offer_expire'];
	
			$this->db->set('offer_expire', "STR_TO_DATE('{$date}', '%d-%m-%Y')", FALSE);
			
			unset($input_data['offer_expire']);
			unset($input_data['expires']);
		}

		$input_data['offer_creation'] = strftime('%F %T');
		$input_data['offer_last_edit'] = strftime('%F %T');
		$input_data['website_id'] = $website_id;
		
		$titles = $input_data['offer_title'];
		$bodies = $input_data['offer_body'];
		unset($input_data['offer_title']);
		unset($input_data['offer_body']);

		try
		{
			$this->db->insert(TABLE_OFFERS, $input_data);
			$insert_id = $this->db->insert_id();
					
			$multilang_offer = array();
			foreach ($titles as $o_lang => $o_title) {
				$multilang_offer[] = array(
					'offer_id' => $insert_id,
					'lang' => $o_lang,
					'offer_title' => $o_title,
					'offer_body' => $bodies[$o_lang]
				);
			}

			$this->db->insert_batch(TABLE_OFFERS_LANGUAGE, $multilang_offer);
			
			return array('status' => TRUE, 'id' => $insert_id);
		}
		catch(Exception $e)
		{
			return array('status' => FALSE, 'error' => $e->getMessage());
		}
	}
	
	// function edit_offer($id, $visible = TRUE, $title = FALSE, $body = FALSE, $author = FALSE, $expire = FALSE, $expire_date = FALSE)
	function edit_offer($id, $data)
	{
		try
		{
			// Removing unused languages
			$result['languages_removed'] = 0;
			if ( ! empty($data['remove_lang'])) {
				foreach ($data['remove_lang'] as $rlang) {
					$this->db->delete(TABLE_OFFERS_LANGUAGE, array('offer_id'=> $id,'lang'=>$rlang) );
					$result['languages_removed']++;
				}
			}
			unset($data['remove_lang']);


			// Updating translations or inserting new ones;
			$result['languages_updated'] = 0;
			if ( ! empty($data['offer_title'])) {
				$multilang_data = array();
				foreach ($data['offer_title'] as $o_lang => $val) {
					$multilang_data[] = array(
						'offer_id' => $id,
						'offer_title' => $val,
						'offer_body' => $data['offer_body'][$o_lang],
						'lang' => $o_lang
					);
				}
				$this->db->insert_on_duplicate_update_batch(TABLE_OFFERS_LANGUAGE, $multilang_data);
				$result['languages_updated'] = $this->db->affected_rows();
			}			
			unset($data['offer_title']);			
			unset($data['offer_body']);
			
			
			$data['offer_last_edit'] = strftime('%F %T');
			
			// If a new image has ben set, remove the old one from the server.
			if( isset($data['offer_image']) )
				$this->_delete_img($id);
			
			if ( empty($data['offer_special']))
				$data['offer_special'] = 0;
			
			// if an expiration date has been set, format it in the right way.
			if( empty($data['offer_expire']) )
			{
				$data['offer_expire']=NULL;
			}
			else
			{
				$date = $data['offer_expire'];

				$this->db->set('offer_expire', "STR_TO_DATE('{$date}', '%d-%m-%Y')", FALSE);

				unset($data['offer_expire']);
				unset($data['expires']);
			}
			
			// Finally updating the offer settings
			$this->db->where('id', $id);
			$this->db->update(TABLE_OFFERS, $data);
			$result['offer_updated'] = $this->db->affected_rows();			
			
			return array('status' => TRUE, 'result' => $result);
		}
		catch(Exception $e)
		{
			return array('status' => FALSE, 'error' => $e->getMessage());
		}
	}
	
	function remove_offer($id)
	{
		try
		{
			$this->db->where('id', $id);
			if($this->db->count_all_results(TABLE_OFFERS) > 0)
			{
				$this->_delete_img($id);
				$this->db->where('id', $id);
				$this->db->delete(TABLE_OFFERS);
				return array('status' => TRUE, 'result' => $this->db->affected_rows());
			}
			else
			{
				return array('status' => FALSE, 'error' => "Offer {$id} doesn't exist!", 'error_code' => 'REM1');
			}
		}
		catch(Exception $e)
		{
			return array('status' => FALSE, 'error' => $e->getMessage());
		}
	}	
	
	function load_offer($id, $language=LANG_DEFAULT, $lang_strict=TRUE)
	{
		$this->db->select("id, id AS offer_id, website_id, offer_creation, DATE_FORMAT(offer_expire, '%d-%m-%Y') as offer_expire, offer_special, offer_visible, offer_image", FALSE)
			->from(TABLE_OFFERS)
			->join(TABLE_USERS, 'author_id = user_id')
			->where('id', $id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			
			if($row->offer_expire == NULL)
				$row->expired = FALSE;
			else
				$row->expired = $this->_is_expired($row->offer_expire);
			
			if($this->_getTranslations($row, $language, $lang_strict))
				return array('status' => TRUE, 'result' => $row);	

			return array('status' => FALSE, 'error_code' => 'OFF2', 'error' => "Error while loading offer #{$id} translation from the database.");
		}
		else
			return array('status' => FALSE, 'error_code' => 'OFF1', 'error' => "Offer {$id} is not in the database.");
	}
	
	
	// -------------- PRIVATE Functions --------------
	
	private function _delete_img($offer_id)
	{
		$this->db->select('offer_image')->from(TABLE_OFFERS)->where('id',$offer_id);
		$query = $this->db->get();
		
		if(isset($query->row()->offer_image))
		{
			$img_name = $query->row()->offer_image;
			unlink(PATH_SRV_UPLOAD.$img_name);	
		}
	}
	

	private function _is_expired($date)
	{
		$today = strtotime(date("d-m-Y"));
		$expire = strtotime($date);
		
		return ($expire < $today);
	}

	private function _getTranslations(&$offer, $lang, $strict)
	{
		if($strict)
		{
			$content = $this->db->get_where(TABLE_OFFERS_LANGUAGE, array('offer_id'=>$offer->id, 'lang'=>$lang))->row();
			// Skips the offer if there's no translation.
			if( empty($content) )
				return FALSE;

			$offer->offer_title = $content->offer_title;
			$offer->offer_body = $content->offer_body;
		}
		else
		{
			$translations = $this->db->get_where(TABLE_OFFERS_LANGUAGE, array('offer_id'=>$offer->id))->result();
			foreach($translations as $trans_row)
			{
				$offer->offer_title_multi[$trans_row->lang] = $trans_row->offer_title;
				$offer->offer_body_multi[$trans_row->lang] = $trans_row->offer_body;
				if( $trans_row->lang == $lang || ( ! isset($offer->offer_title) && $trans_row->lang == LANG_DEFAULT) )
				{
					$offer->offer_title = $trans_row->offer_title;
					$offer->offer_body = $trans_row->offer_body;
				}
			}
		}
		
		return TRUE;
	}
}

/* End of file offers_model.php */
/* Location: ./application/models/offers_model.php */