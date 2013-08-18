<?php
class ESO_DB_mysql_driver extends CI_DB_mysql_driver {

	final public function __construct($params)
	{
		parent::__construct($params);
	}

	/**
	* Insert_On_Duplicate_Update_Batch
	*
	* Compiles batch insert strings and runs the queries
	* MODIFIED to do a MySQL 'ON DUPLICATE KEY UPDATE'
	*
	* @access public
	* @param string the table to retrieve the results from
	* @param array an associative array of insert values
	* @return object
	*/
	function insert_on_duplicate_update_batch($table = '', $set = NULL)
	{
		
		if ( ! is_null($set))
		{
			$this->set_insert_batch($set);
		}

		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				//No valid data array.  Folds in cases where keys and values did not match up
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}

		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}

		// Batch this baby
		for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100)
		{

			$sql = $this->_insert_on_duplicate_update_batch($this->_protect_identifiers($table, TRUE, NULL, FALSE), $this->ar_keys, array_slice($this->ar_set, $i, 100));

			// echo $sql;

			$this->query($sql);
		}

		$this->_reset_write();


		return TRUE;
	}
	
  /** 
   * This function will allow you to do complex group where clauses in to c and (a AND b) or ( d and e)
   * This function is needed as else the where clause will append an automatic AND in front of each where Thus if you wanted to do something
   * like a AND ((b AND c) OR (d AND e)) you won't be able to as the where would insert it as a AND (AND (b...)) which is incorrect. 
   * Usage: start_group_where(key,value)->where(key,value)->close_group_where() or complex queries like
   *        open_bracket()->start_group_where(key,value)->where(key,value)->close_group_where()
   *        ->start_group_where(key,value,'','OR')->close_group_where()->close_bracket() would produce AND ((a AND b) OR (d))
   * @param $key mixed the table columns prefix.columnname
   * @param $value mixed the value of the key
   * @param $escape string any escape as per CI
   * @param $type the TYPE of query. By default it is set to 'AND' 
   * @return db object.  
   */
  function start_group_where($key,$value=NULL,$escape=TRUE,$type="AND")
  {
      $this->open_bracket($type); 
      return parent::_where($key, $value,'',$escape); 
  }

  /**
   * Strictly used to have a consistent close function as the start_group_where. This essentially callse the close_bracket() function. 
   */
  function close_group_where()
  {
      return $this->close_bracket();  
  }

  /**
   * Allows to place a simple ( in a query and prepend it with the $type if needed. 
   * @param $type string add a ( to a query and prepend it with type. Default is $type. 
   * @param $return db object. 
   */
  function open_bracket($type="AND")
  {
      $this->ar_where[] = $type . " (";
      return $this;  
  }   

  /**
   * Allows to place a simple ) to a query. 
   */
  function close_bracket()
  {
      $this->ar_where[] = ")"; 
      return $this;       
  }

	/**
	* Insert_on_duplicate_update_batch statement
	*
	* Generates a platform-specific insert string from the supplied data
	* MODIFIED to include ON DUPLICATE UPDATE
	*
	* @access public
	* @param string the table name
	* @param array the insert keys
	* @param array the insert values
	* @return string
	*/
	private function _insert_on_duplicate_update_batch($table, $keys, $values)
	{
		foreach($keys as $key)
			$update_fields[] = $key.'=VALUES('.$key.')';

		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES ".implode(', ', $values)." ON DUPLICATE KEY UPDATE ".implode(', ', $update_fields);
	}

}

