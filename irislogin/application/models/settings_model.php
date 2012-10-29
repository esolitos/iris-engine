<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_Model extends CI_Model
{
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function createOption($opt_name)
	{
		$this->db->insert(TABLE_OPTIONS_NAMES, array('machine_name' => $opt_name));

		return $this->db->insert_id();
	}

	function getOptionID($opt_name)
	{
		$query = $this->db->get_where(TABLE_OPTIONS_NAMES, array('machine_name' => $opt_name), 1, 0);

		if($query->num_rows())
		{
			return $query->row()->option_id;
		}

		return NULL;
	}

	function getOption($web_id, $serv_id, $opt_id)
	{
		$where = array(
			'website_id' => $web_id,
			'service_id' => $serv_id,
			'option_id' => $opt_id
			);
		
		$this->db->select('value');
		
		$query = $this->db->get_where(TABLE_OPTIONS, $where, 1, 0);
		
		if($query->num_rows())
		{
			return $query->row()->value;
		}

		return NULL;
	}

	function updateOption($web_id, $serv_id, $opt_id, $value)
	{
		if($this->getOption($web_id, $serv_id, $opt_id) === NULL)
			return $this->setOption($web_id, $serv_id, $opt_id, $value);
		else
		{
			$where = array(
				'website_id' => $web_id,
				'service_id' => $serv_id,
				'option_id' => $opt_id
				);
		
			return $this->db->update(TABLE_OPTIONS, array('value' => $value), $where);
		}
	}
	
	function setOption($web_id, $serv_id, $opt_id, $value)
	{
		$data = array(
			'website_id' => $web_id,
			'service_id' => $serv_id,
			'option_id' => $opt_id,
			'value' => $value
			);
		
		$this->db->insert(TABLE_OPTIONS, $data);
		
		return $this->db->affected_rows();
	}
	
	function getOptionArray($web_id, $serv_id, $opt_array)
	{
		$options = array();
		foreach ($opt_array as $option)
		{
			$prop_id = $this->getOptionID($option);
			if($option AND $prop_id !== NULL)
				$options[$option] = $this->getOption($web_id, $serv_id, $prop_id);
		}
		return $options;
	}


}

