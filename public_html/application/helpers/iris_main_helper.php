<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( ! function_exists('init_viewdata') )
{
	function init_viewdata($other_data=NULL)
	{
		$data = array();
		$data['message'] = FALSE;
		$data['error'] = FALSE;
		
		if(is_array($other_data))
		{
			foreach($other_data as $key=>$val)
				$data[$key] = $val;
		}
		
		return $data;
	}
	
	function init_headdata($other_data=NULL)
	{
		$data = array();
		$data['head'] = array();
		$data['page_title'] = "Iris Login";

		if(is_array($other_data))
		{
			foreach($other_data as $key=>$val)
				$data[$key] = $val;
		}

		return $data;
	}
	
	function output($views=FALSE)
	{
		$CI =& get_instance();
		
		$footer=FALSE;
		$website_data = array();
		$user = $CI->session->all_userdata();
		
		if(isset($user['user_website']))
		{
			$website_data = $CI->websites_model->load_website_data($user['user_website'], TRUE);
		}
		else
		{
			$user['user_website'] = FALSE;
		}

		// Fixing the user-data, i don't like using isset() on a view
		if( ! isset($user['logged_in']))
			$user['logged_in'] = FALSE;
		
		if( ! is_array($views))
		{
			show_error("Errore nella generazione dell'output.");
		}
		else
		{
			// If the request include to show the header
			if(isset($views['header']))
			{
				$views['header']['user'] = $user;
				$views['header']['website'] = $website_data;
				$CI->load->view('common/header_view', $views['header']);
				unset($views['header']);
			}
			
			// If the request include to show the footer
			if(isset($views['footer']))
			{
				$footer = $views['footer'];
				unset($views['footer']);
			}
			
			// We're asking to show an error?
			if(isset($views['error']))
			{
				$_error =& load_class('Exceptions', 'core');
				$page['err'] = $_error->show_error($views['error']['head'], $views['error']['message'], 'error_general', $views['error']['code']);
				$CI->load->view('common/error', $page);
				
				// show_error($views['error']['message'], $views['error']['code'], $views['error']['head']);
			}
			else
			{
				// Otherwise we show all the requested views with associated data!
				foreach($views as $v_name=>$v_data)
				{
					$CI->load->view($v_name, $v_data);
				}
			}
			
			// finally showing the footer if requestes
			if($footer)
				$CI->load->view('common/footer_view', $footer);
		}
	}
	
	
	
	function check_date_it($date)
	{
		if( ! preg_match("/\d{1,2}-\d{1,2}-\d{4}/i", $date))
			return FALSE;

		$date=explode('-',$date);
		return checkdate($date[1], $date[0], $date[2]);

	}
	
	function check_date($date)
	{
		if( ! preg_match("/\d{4}-\d{1,2}-\d{1,2}/i", $date))
			return FALSE;

		$date=explode('-',$date);
		return checkdate($date[1], $date[2], $date[0]);

	}
	
	
	function subscription_status($website, $service_name)
	{
		$CI =& get_instance();
		
		$subscr = $CI->websites_model->get_services_for_website($website, 'name');
		
		if (isset($subscr[$service_name]))
			return ! $subscr[$service_name]->expired;
		else
			return FALSE;
	}
	
	function outputMyDate($date, $delim="-", $delim_out=".")
	{
		$split = explode($delim, $date);
		
		return $split[2].$delim_out.$split[1].$delim_out.$split[0];
	}
	
	function inputMyDate($y, $m, $d)
	{
		
	}
	
	function time_diff($s, $text = TRUE)
	{
	    $m=0;$hr=0;$d=0;$td=0;
		if($text)
		{
		    if($s>59) { 
		        $m = (int)($s/60); 
		        $s = $s-($m*60); // sec left over 
		        $td = "$m min";
		    } 
		    if($m>59){ 
		        $hr = (int)($m/60); 
		        $m = $m-($hr*60); // min left over 
		        $td = "$hr hr"; if($hr>1) $td .= "s"; 
		        if($m>0) $td .= ", $m min"; 
		    } 
		    if($hr>23){ 
		        $d = (int)($hr/24); 
		        $hr = $hr-($d*24); // hr left over 
		        $td = "$d day"; if($d>1) $td .= "s"; 
		        if($d<3){ 
		            if($hr>0) $td .= ", $hr hr"; if($hr>1) $td .= "s"; 
		        } 
		    }
		}
		else
		{
			if($s>59) { 
		        $m = (int)($s/60); 
		        $td = 0;
		    } 
		    if($m>59){ 
		        $hr = (int)($m/60); 
		        $m = $m-($hr*60); // min left over 
		        $td = 0; 
		    } 
		    if($hr>23){ 
		        $d = (int)($hr/24); 
		        $hr = $hr-($d*24); // hr left over 
		        $td = $d;
		    }
		}
	    return $td; 
	}
	
	function post_as_json($request_url, $data)
	{
		$data_string = json_encode($data);

		$result = file_get_contents(
						$request_url,
						null,
						stream_context_create(
							array(
								'http' => array(
											'method' => 'POST',
											'header' => 'Content-Type: application/json'."\r\n".
														'Content-Length: ' . strlen($data_string)."\r\n",
											'content' => $data_string,
											// 'user_agent'=>    $_SERVER['HTTP_USER_AGENT'],
											'user_agent'=>    "iris-login-server",
											),
								)
						)
					);

		return json_decode($result);
	}
	
	function mkdir_if($dirname='', $perm = '0775')
	{
		if ( ! file_exists($dirname))
			return mkdir($dirname, $perm, TRUE);
		
		return TRUE;
	}
	
	function unlink_if($filename='')
	{
		if (file_exists($filename))
			return unlink($filename);
		
		return TRUE;
	}
}