<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class ESO_Loader extends CI_Loader
{

	// Esolitos Add for showing all the cached vars
    public function get_cached_vars()
    {
		if(
			(strstr($_SERVER['HTTP_HOST'],'.dev') OR strstr($_SERVER['HTTP_HOST'],'test'))
			AND DEBUG
			)
			return $this->_ci_cached_vars;
		else
			return array();
    }

}