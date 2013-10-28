<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config = array();

switch (TRUE) {

	case (strstr($_SERVER['HTTP_HOST'],'esolitos.com')):
		$config = array(
			'useragent'		=>	'irislogin',
			'protocol'		=> 	'sendmail',
			'mailpath'		=>	'/usr/sbin/sendmail',
			'charset'		=>	'utf-8',
			'mailtype'		=>	'html',
			);
		break;


	case (strstr($_SERVER['HTTP_HOST'],'dev')):
	case (strstr($_SERVER['HTTP_HOST'],'test')):
	default:
		$config = Array(
			'useragent'		=>	'irislogin',
			'crlf'			=> "\r\n",
			'newline'		=> "\r\n",
			'charset'		=> 'utf-8',
			'mailtype'		=> 'html',
			
		    'protocol' => 'smtp',
			'useragent'		=>	'irislogin',
		    'smtp_host' => 'ssl://smtp.googlemail.com',
		    'smtp_port' => 465,
		    'smtp_user' => 'no-reply@irislogin.it',
		    'smtp_pass' => 'IRISLOGIN2012-no-login',

			'bcc_batch_mode'=> TRUE,
			'bcc_batch_size'=> '100'
		);
		break;
}


