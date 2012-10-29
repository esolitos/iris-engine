<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reservations_Model extends CI_Model
{
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function get_one($id=FALSE)
	{
		if($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get(TABLE_RESERVATIONS);
			
			if($query->num_rows() == 1)
			{
				$this->mask_as($id, 'seen');
				return $query->row();
			}
		}
		
		return FALSE;
	}
	
	function get_for($w_id=FALSE, $filter='all')
	{
		if($w_id)
		{
			$data = array();
			
			if($w_id!='all')
				$this->db->where('website_id', $w_id);
				
			if($filter=='new')
				$this->db->where('seen', 0);
			elseif($filter=='old')
				$this->db->where('to_date <', 'CURDATE()', FALSE);
			else
				$this->db->where('to_date >=', 'CURDATE()', FALSE);
				
			$query = $this->db->get(TABLE_RESERVATIONS);
			
			foreach($query->result() as $row)
				$data[$row->id] = $row;
				
			return $data;
		}
		
		return FALSE;
	}
	
	
	function mask_as($id, $status='seen', $value = 1)
	{
		if(is_array($id))
			foreach ($id as $rid)
				$this->mask_as($rid, $status, $value);
		else
			return $this->db->update(TABLE_RESERVATIONS, array($status => $value), array('id' => $id));
		
		return FALSE;
	}
	
	
	function add_reservation($data)
	{
		$from = strtotime($data['from_date']);
		$data['from_date'] = date("Y-m-d", $from);
		
		$to = strtotime($data['to_date']);
		$data['to_date'] = date("Y-m-d", $to);

			
		$this->db->set('time', 'NOW()', FALSE);
		$this->db->insert(TABLE_RESERVATIONS, $data);
		
		return $this->db->insert_id();
	}

}


/* End of file reservations_model.php */
/* Location: ./application/models/reservations_model.php */