<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_Model extends CI_Model
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
		
		$query = $this->db->get(TABLE_USERS);
		if($complete)
			foreach($query->result() as $row)
				$data[$row->user_id] = $this->load_user_data($row->user_id);
		else
			foreach($query->result() as $row)
			{
				unset($row->pass);
				$data[$row->user_id] = $row;
			}

		return $data;
	}
	
	function count()
	{
		return $this->db->count_all(TABLE_USERS) -1 ; // Rimuovo l'utente admin.
	}
	
	function add($username, $email, $website)
	{
		// Check if the user is not taken
		$this->db->where('username', $username);
		if($this->db->count_all_results(TABLE_USERS) != 0)
			return array('status' => FALSE, 'err_code' => 1, 'err_descr' => "Username già in uso.");
		
		
		// Check if the email has ben already used by someone else
		$this->db->where('email',$email);
		if($this->db->count_all_results(TABLE_USERS) != 0)
			return array('status' => FALSE, 'err_code' => 2, 'err_descr' => "Email già in uso.");
		
		
		// If everyting is all-right adds the user with the default passowrd
		$u_id = $this->login->create($email, $username, DEFAUT_PASSWORD, $website, FALSE);

		if($u_id !== FALSE)
			return array('status' => TRUE, 'user_id' => $u_id, 'username' => $username, 'email' => $email);
		else
			return array('status' => FALSE, 'err_code' => 3, 'err_descr' => "Errore durante l'aggiunta dell'utente {$username} con email: {$email}");
	}
	
	function login($username, $password, $dummy = FALSE)
	{
		if($dummy)
			return $this->login->check_password($username, $password);
		else
			return $this->login->login($username, $password);
	}
	
	function logout()
	{
		return $this->login->logout();
	}

	
	function delete_user($user)
	{
		if(is_numeric($user))
			return $this->login->delete($user);
		
		//Check against user table (allow login with username or email)
		$this->db->select('user_id')->where('email', $user)->or_where('username', $user); 
		$query = $this->CI->db->get_where(TABLE_USERS);
		
		if ($query->num_rows() > 0) 
		{
			$uid = $query->row()->user_id;
			return $this->login->delete($uid);
		}
		
		return FALSE;
	}
	
	function update_pwd($user, $new_pass=DEFAUT_PASSWORD)
	{
		if(is_numeric($user))
			return $this->login->update_password($user, $new_pass);

		$this->db->select('user_id')->where('email', $user)->or_where('username', $user); 
		$query = $this->db->get(TABLE_USERS);
		
		if ($query->num_rows() > 0) 
		{
			$uid = $query->row()->user_id;
			return $this->login->update_password($uid, $new_pass);
		}
		
		return FALSE;
		
	}

	function get_users_for_website($website=FALSE)
	{
		if(is_numeric($website))
		{
			$users=array();
			$this->db->select('user_id, user_website, username, email, reg_date, user_last_login');
			$this->db->from(TABLE_USERS);
			$this->db->where('user_website', $website);
			
			$query = $this->db->get();
			
			foreach($query->result() as $row)
				$users[] = $row;
			
			return $users;
		}
		
		return FALSE;
	}

	function get_uid($user)
	{
		$this->db->select('user_id as id');
		$this->db->where('username', $user)->or_where('email', $user);
		$result = $this->db->distinct()->get(TABLE_USERS);
		
		if($result->num_rows() == 1)
		{
			return $result->row()->id;
		}

		return FALSE;
	}

	function load_user_data($user)
	{
		$uid = 0;
		if( ! is_numeric($user))
		{
			if(($uid = $this->get_uid($user)) === FALSE)
				return FALSE;
		}
		else
			$uid = $user;
			
		$data = array();
		$websites = array();
		
		$this->db->select('user_id AS id, username, email, reg_date, last_modified AS last_edit, user_last_login, user_website');
		$this->db->from(TABLE_USERS);
		$this->db->where('user_id', $uid);
		
		$query = $this->db->get();
		$data['info'] = $query->row();
		
		$query->free_result();
		
		$this->db->select('website_id AS id, website_url AS url, website_name AS name');
		$this->db->from(TABLE_WEBSITES);
		$this->db->where('website_id', $data['info']->user_website);
		
		$query = $this->db->get();
		$data['user_websites'] = $query->row();
		
		return $data;
	}
	
}

/* End of file users_model.php */
/* Location: ./application/models/users_model.php */