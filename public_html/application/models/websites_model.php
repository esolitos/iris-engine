<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Websites_Model extends CI_Model
{
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('SimpleLoginSecure', '', 'login');
    }

	function get_all($complete=FALSE)
	{	
		$data = array();
		
		$query = $this->db->where('website_id !=', WEBSITE_ID_IRIS)->get(TABLE_WEBSITES);
		if($complete)
			foreach($query->result() as $row)
				$data[$row->website_id] = $this->load_website_data($row->website_id, TRUE);
		else
			foreach($query->result() as $row)
				$data[$row->website_id] = $row;

		return array(
			'success' => TRUE,
			'total' => $query->num_rows(),
			'data' =>$data
			);
	}
	
	function get_services()
	{
		$data = array();
		$query = $this->db->get(TABLE_SERVICES);

		foreach($query->result() as $row)
		{
			$row->service_id = $row->id; // Compatibility
			$data[$row->id] = $row;
		}

		return $data;
	}
	
	function add_website($name, $url)
	{
		$this->db->where('website_url', $url);
		if($this->db->count_all_results(TABLE_WEBSITES) != 0)
			return array('status' => FALSE, 'error_code' => 1, 'error' => "Website already in the database!");
		
		$data = array(
						'website_url' => $url,
						'website_name' => $name
					 );
		$this->db->insert(TABLE_WEBSITES, $data);
		
		if($this->db->affected_rows() > 0)
			return array('status' =>TRUE, 'user_website' => $this->db->insert_id());
		else
			return array('status' => FALSE, 'error_code' => 2, 'error' => "Error while adding website in the database!");

	}
	
	function delete_website($id)
	{
		if(is_numeric($id))
			return $this->db->delete(TABLE_WEBSITES, array('website_id' => $id));
		else
			return FALSE;
	}

	function edit_website($id, $data)
	{
		$this->db->where('website_id', $id);
		$this->db->set($data);
		if($this->db->update(TABLE_WEBSITES))
			return array('status' => TRUE, 'affected' => $this->db->affected_rows());
		else
			return array('status' => FALSE, 'error_code' => 1, 'error' => "Error while editing website!", 'affected' => $this->db->affected_rows());
	}
	
	function add_service($data)
	{				
		$this->db->insert(TABLE_SUBSCRIPTIONS, $data);
		
		if($this->db->affected_rows() > 0)
			return array('status' => TRUE);
		else
			return array('status' => FALSE, 'err_code' => 1, 'err_descr' => "Error while adding the subscription!");
		
	}
	
	function edit_service($serv_name, $website_id, $expire='0000-00-00')
	{
		$exec = FALSE;
 		$svc = $this->db->get_where(TABLE_SERVICES, array('service_name' => $serv_name))->row();

		$this->db->where('website_id', $website_id)->where('service_id', $svc->id);
		
		if($expire===TRUE)
			$exec = $this->db->delete(TABLE_SUBSCRIPTIONS);
		else
			$exec = $this->db->update(TABLE_SUBSCRIPTIONS, array('subscr_expire' => $expire));		
		
		if($exec === TRUE)
			return array('status' => TRUE, 'affected' => $this->db->affected_rows());
		else
			return array('status' => FALSE, 'error_code' => 1, 'error' => "Error while editing subscription!", 'affected' => $this->db->affected_rows());
	}
	
	function get_services_for_website($website_id, $array_by='id')
	{
		if( ! is_numeric($website_id) OR $website_id<=0)
		{
			return FALSE;
		}
		else
		{
			$services = array();
			$this->db->from(TABLE_SUBSCRIPTIONS)->join(TABLE_SERVICES, 'service_id = id')->where('website_id', $website_id);
			$query = $this->db->get();
			
			switch ($array_by) {
				case 'both':
					foreach ($query->result() as $row)
					{
						$row->expired = $this->_is_expired($row->subscr_expire);
						$services[$row->service_name] = $row;
					    $services[$row->service_id] = $row;
					}
					break;
					
				case 'name':
					foreach ($query->result() as $row)
					{
						$row->expired = $this->_is_expired($row->subscr_expire);
					    $services[$row->service_name] = $row;
					}
					break;
				
				case 'id':
				default:
					foreach ($query->result() as $row)
					{
						$row->expired = $this->_is_expired($row->subscr_expire);
						$services[$row->service_id] = $row;
					}
			}
			
			
			return $services;
		}
	}
	
	function load_website_data($website_id, $complete=TRUE)
	{
		$data = array();
		$users = array();
		$services = array();
		$services_list = array();
		
		$this->db->from(TABLE_WEBSITES)->join(TABLE_NEWSLETTER, TABLE_WEBSITES.'.website_id = '.TABLE_NEWSLETTER.'.website_id', 'left');
		$this->db->where(TABLE_WEBSITES.'.website_id', $website_id);
		$query = $this->db->get();
		$data['info'] = $query->row();
		

		if($complete)
		{
			// Getting User informations
			$this->db->select('user_id, username,  email');
			$this->db->from(TABLE_USERS);
			$this->db->where('user_website', $website_id);

			$query = $this->db->get();
			foreach($query->result() as $row)
				$users[$row->user_id] = $row;

			unset($users[1]);
			$query->free_result();
		
			// Getting Services informations
			$this->db->select('id AS service_id, service_name, service_price, service_url, subscr_expire AS service_expire');
			$this->db->from(TABLE_SUBSCRIPTIONS)->join(TABLE_SERVICES, 'service_id = id');
			$this->db->where('website_id', $website_id);
		
			$query = $this->db->get();
			foreach($query->result() as $row)
			{
				$services_list[$row->service_name] = $this->_count_days($row->service_expire, FALSE);
				// $row->days = $this->_count_days($row->service_expire);
				$row->expired = ($this->_count_days($row->service_expire, FALSE) <= 0);
				
				// $services[$row->service_id] = $row;
				$services[$row->service_name] = $row;
			}

			$query->free_result();
		}
		
		$data['users'] = $users;
		$data['services'] = $services;
		$data['services_list'] = $services_list;
		
		return $data;
	}


/* -------------------------- Styles -------------------------- */

	function get_style($website, $service)
	{
		$query = $this->db->select('style')->get_where(TABLE_STYLE, array('website_id'=>$website, 'service_id'=>$service), 1, 0);
		
		if($query->num_rows() == 1)
			if($query->row()->style != NULL)
				return "/".PATH_WEB_UPLOAD.$query->row()->style;
				
		return "/public/css/defaults/services-style.min.css";
	}
	
	function add_style($website, $service, $file_name)
	{
		$data = array(
			'website_id'	=> $website,
			'service_id'	=> $service,
			'style'			=> $file_name,
			);
			
		return $this->db->insert(TABLE_STYLE, $data);
	}

	function remove_style($website, $service)
	{
		$this->db->delete(TABLE_STYLE, array('website_id'=>$website, 'service_id'=>$service));
		if($this->db->affected_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}
	
	function get_special_img($website)
	{
		$query = $this->db->select('special_img')->get_where(TABLE_WEBSITES, array('website_id'=>$website), 1, 0);
		
		if($query->num_rows() == 1)
			return $query->row()->special_img;
		else
			return FALSE;
	}
	
	function set_special_img($website, $file_name)
	{
		$data = array(
			'website_id'	=> $website,
			'special_img'	=> $file_name,
			);
			
		return $this->db->update(TABLE_WEBSITES, $data);
	}
	

/* -------------------------- Private Functions -------------------------- */

	private function _is_expired($date)
	{
		// $today = strtotime(date("Y-m-d"));
		$today = time();
		$expire = strtotime($date);
		
		return ($expire < $today);
	}

	private function _count_days($date, $text=TRUE)
	{
		$expire = strtotime($date);
		
		return time_diff($expire - time(), $text);
	}
}

/* End of file users_model.php */
/* Location: ./application/models/users_model.php */