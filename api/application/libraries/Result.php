<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* Classe utilizzata per strasmettere le informazioni da un model ad un controller
*/
class Result
{
	private $CI; //CodeIgniter object
	private $error_code; //Univoque error code 
	private $error_text; //Error trnaslation in human language
	private $result_data; //Container for resulting data
	private $result_counter; // counter of the results
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	public function set($data, $count=FALSE)
	{
		$this->result_data = $data;

		if(is_numeric($count))
			$this->result_counter = $count;
	}
	
	public function add($data, $key=FALSE)
	{
		if(is_null($data))
			$this->result_data = $data;

		else if(is_array($this->result_data) AND $key)
			$this->result_data[$key] = $data;

		else if(is_array($this->result_data))
			$this->result_data = array_merge($this->result_data, $data);

		else
			$this->result_data = array_merge(array($this->result_data), $data);
	}
	
	public function get($default = FALSE)
	{
		if($this->error_code === FALSE OR is_null($this->result_data))
			return FALSE;
		else
			return $this->result_data;
	}
	
	public function getCount()
	{
		if(is_numeric($this->result_counter))
			return $this->result_counter;
		else
			return 0;
	}

	public function setError($code, $text = FALSE)
	{
		$this->error_code = $code;
		if($text)
			$this->error_text = $text;
	}
	
	public function getError($lang = 'ita')
	{
		// $this->CI->language->get_line()
	}
}
