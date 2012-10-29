<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');

define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', FALSE);

/**
 * SimpleLoginSecure Class
 *
 * Makes authentication simple and secure.
 *
 * Simplelogin expects the following database setup. If you are not using 
 * this setup you may need to do some tweaking.
 *   
 * 
 *   CREATE TABLE `users` (
 *     `user_id` int(10) unsigned NOT NULL auto_increment,
 *     `email` varchar(255) NOT NULL default '',
 *     `pass` varchar(60) NOT NULL default '',
 *     `reg_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Creation date',
 *     `last_modified` datetime NOT NULL default '0000-00-00 00:00:00',
 *     `last_login` datetime NULL default NULL,
 *     PRIMARY KEY  (`user_id`),
 *     UNIQUE KEY `email` (`email`),
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * 
 * @package   SimpleLoginSecure
 * @version   1.0.1
 * @author    Alex Dunae, Dialect <alex[at]dialect.ca>, extended by Esolitos Marlon <jj.69kt@gmail.com>
 * @copyright Copyright (c) 2008, Alex Dunae
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://dialect.ca/code/ci-simple-login-secure/
 */
class SimpleLoginSecure
{
	var $CI;
	var $user_table = TABLE_USERS;

	/**
	 * Create a user account
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	int (new user id)
	 */
	function create($user_email ='', $username = '', $user_pass = DEFAUT_PASSWORD, $website, $auto_login = TRUE) 
	{
		$this->CI =& get_instance();

		//Make sure account info was sent
		if($user_email == '')
			return FALSE;
			
		// If no username supplied we use the email as username!
		if($username == '')
			$username = $user_email;
		
		//Check against user table
		## ESO-NB: Gia' fatto nel model!
		// $this->CI->db->where('email', $user_email); 
		// $query = $this->CI->db->get_where($this->user_table);
		// if ($query->num_rows() > 0) //user_email already exists
		// 	return FALSE;

		//Check against user table
		## ESO-NB: Gia' fatto nel model!
		// $this->CI->db->where('username', $username); 
		// $query = $this->CI->db->get_where($this->user_table);
		// if ($query->num_rows() > 0) //username already exists
		// 	return FALSE;
			
		//Hash user_pass using phpass
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		$user_pass_hashed = $hasher->HashPassword($user_pass);

		//Insert account into the database
		$data = array(
					'email' => $user_email,
					'username' => $username,
					'pass' => $user_pass_hashed,
					'user_website' => $website,
					'reg_date' => date("Y-m-d H:i:s"),
					'last_modified' => date("Y-m-d H:i:s"),
				);

		$this->CI->db->set($data); 

		if( ! $this->CI->db->insert($this->user_table)) //There was a problem! 
			return FALSE;						
				
		if($auto_login)
			$this->login($user_email, $user_pass);
		
		return $this->CI->db->insert_id();
	}
	
	/**
	 * Updates the new password af an user
	 *
	 * @access public
	 * @param numeric
	 * @param string
	 * @return bool
	 */
	function update_password($uid, $new_pass)
	{
		$this->CI =& get_instance();
		
		if(is_numeric($uid))
		{
			//Hash user_pass using phpass
			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
			$user_pass_hashed = $hasher->HashPassword($new_pass);
		
			$this->CI->db->trans_begin();
			$this->CI->db->where('user_id', $uid);
			$this->CI->db->update($this->user_table, array('pass' => $user_pass_hashed));
			
			if($this->CI->db->affected_rows() !== 1 OR $this->CI->db->trans_status() === FALSE)
			{
				$this->CI->db->trans_rollback();
				return FALSE;
			}
			else
			{
				 $this->CI->db->trans_commit();
				return TRUE;
			}
		}
		else 
			return FALSE;
	}

	/**
	 * Login and sets session variables
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function login($username = '', $user_pass = '') 
	{
		$this->CI =& get_instance();

		if($username == '' OR $user_pass == '')
			return FALSE;


		//Check if already logged in
		if($this->CI->session->userdata('username') == $username)
			return TRUE;
		
		
		//Check against user table (allow login *only* with username)
		$this->CI->db->where('username', $username); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() > 0) 
		{
			$user_data = $query->row_array(); 

			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

			if( ! $hasher->CheckPassword($user_pass, $user_data['pass']))
				return FALSE;

			//Destroy old session
			$this->CI->session->sess_destroy();
			
			//Create a fresh, brand new session
			$this->CI->session->sess_create();

			$this->CI->db->simple_query('UPDATE ' . $this->user_table  . ' SET last_login = NOW() WHERE user_id = ' . $user_data['user_id']);
			
			//Set session data
			unset($user_data['pass']);
			unset($user_data['date']);
			unset($user_data['modified']);
			$user_data['logged_in'] = TRUE;
			$this->CI->session->set_userdata($user_data);
			
			return TRUE;
		} 
		else 
		{
			return FALSE;
		}	

	}

	/**
	 * Logout user
	 *
	 * @access	public
	 * @return	void
	 */
	function logout()
	{
		$this->CI =& get_instance();		

		$this->CI->session->sess_destroy();
		
		//brand new session!
		$this->CI->session->sess_create();
	}

	/**
	 * Delete user
	 *
	 * @access	public
	 * @param integer
	 * @return	bool
	 */
	function delete($user_id) 
	{
		$this->CI =& get_instance();
		
		if(!is_numeric($user_id))
			return FALSE;			

		return $this->CI->db->delete($this->user_table, array('user_id' => $user_id));
	}
	
}
?>
