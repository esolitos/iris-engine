<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Offers_Model extends CI_Model
{
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();

    }
	
	// Returns an array of offers, NULL with no results and FALSE with error.
	function get_offers($website=NULL)
	{
		try
		{
			$this->db->select("id, username AS author, offer_title, offer_body, DATE_FORMAT(offer_creation, '%d-%m-%Y %H:%i') as offer_creation, offer_last_edit, DATE_FORMAT(offer_expire, '%d-%m-%Y') as offer_expire, offer_special, offer_image, offer_visible, , website_name", FALSE);
			$this->db->from(TABLE_OFFERS)->join(TABLE_USERS, 'author_id = user_id')->join(TABLE_WEBSITES, TABLE_OFFERS.".website_id=".TABLE_WEBSITES.".website_id");
			$this->db->order_by('offer_creation', 'ASC');
			
			if($website != NULL)
				$this->db->where(TABLE_OFFERS.".website_id", $website);
			
			// if($this->db->count_all_result() === 0)
			// 	return array('status' => FALSE, 'error' => 'There are no offers for this website!');

			$results=array();
			$query = $this->db->get();
			foreach($query->result() as $row)
			{
				unset($row->pass);	#!!! Remove password...
				if($row->offer_expire == NULL)
					$row->expired = FALSE;
				else
					$row->expired = $this->_is_expired($row->offer_expire);
				
				$results[]=$row;
			}
				
			return array('status' => TRUE, 'result' => $results);

		}
		catch (Exception $e)
		{
			return array('status' => FALSE, 'error' => $e->getMessage());
		}
	}
	
	
	function get_offers_title($website=NULL, $limit = FALSE)
	{
		try
		{
			$this->db->select("id, offer_title, offer_special", FALSE);
			$this->db->from(TABLE_OFFERS);
			$this->db->where('offer_visible', 1);
			$this->db->where('( offer_expire > "'. date("Y-m-d") . '" OR offer_expire IS NULL )');
			$this->db->order_by('offer_special', 'DESC')->order_by('offer_creation', 'DESC');
			
			if($website != NULL)
				$this->db->where(TABLE_OFFERS.".website_id", $website);
			if( $limit )
				$this->db->limit($limit);
			
			$results = $this->db->get()->result();
				
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
		if( ! isset($input_data['expires']))
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
		
		try
		{
			$this->db->insert(TABLE_OFFERS, $input_data);
			return array('status' => TRUE, 'id' => $this->db->insert_id());
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
			$data['offer_last_edit'] = strftime('%F %T');
			
			if(isset($data['offer_image']))
				$this->_delete_img($id);
			
			if( ! isset($data['expires']))
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
			
			
			$this->db->where('id', $id);
			$this->db->update(TABLE_OFFERS, $data);
			
			return array('status' => TRUE, 'result' => $this->db->affected_rows());
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
	
	function load_offer($id)
	{
		$this->db->select("id AS offer_id, website_id, offer_title, offer_body, offer_creation, DATE_FORMAT(offer_expire, '%d-%m-%Y') as offer_expire, offer_special, offer_visible, offer_image", FALSE);
		$this->db->from(TABLE_OFFERS)->join(TABLE_USERS, 'author_id = user_id');
		$this->db->where('id', $id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			
			if($row->offer_expire == NULL)
				$row->expired = FALSE;
			else
				$row->expired = $this->_is_expired($row->offer_expire);
				
			return array('status' => TRUE, 'result' => $row);
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
}

/* End of file offers_model.php */
/* Location: ./application/models/offers_model.php */