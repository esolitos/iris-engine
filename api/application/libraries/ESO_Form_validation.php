<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ESO_Form_validation extends CI_Form_validation {

	function __construct()
	{
		parent::__construct();
		$this->set_message('valid_url', 'The field %s must contain a valid URL.');
		$this->set_message('in_database', 'The field %s must be in the database.');
	}

    function valid_url($str)
{
	
		// SCHEME
		$urlregex = "^(https?|ftp)\:\/\/";

		// USER AND PASS (optional)
		$urlregex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";

		// HOSTNAME OR IP
		// $urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*"; // http://x = allowed (ex. http://localhost, http://routerlogin)
		$urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)+"; // http://x.x = minimum
		// $urlregex .= "([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}"; // http://x.xx(x) = minimum
		//use only one of the above

		// PORT (optional)
		$urlregex .= "(\:[0-9]{2,5})?";
		// PATH (optional)
		$urlregex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
		// GET Query (optional)
		$urlregex .= "(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?";
		// ANCHOR (optional)
		$urlregex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

		if (! preg_match($urlregex."^", $str))
		{
			return FALSE;
		}

		return TRUE;
    }

	function in_database($str, $table)
	{
		list($table, $field) = explode('.', $table);
		
        $query = $this->CI->db->get_where($table, array($field => $str), 1, 0);
        if ($query->num_rows() > 0) {
            return TRUE;
        }

        return FALSE;
	}

}