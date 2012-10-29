<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config = array();

switch (TRUE) {

	case (strstr($_SERVER['HTTP_HOST'],'dev')):
	case (strstr($_SERVER['HTTP_HOST'],'esolitos.com')):
		$config = array(
			'useragent'		=>	'irislogin',
			'protocol'		=> 	'sendmail',
			'mailpath'		=>	'/usr/sbin/sendmail',
			'charset'		=>	'utf-8',
			'mailtype'		=>	'html',
			);
		break;



	case (strstr($_SERVER['HTTP_HOST'],'test')):
	default:
		$config = array(
			'useragent'		=>	'irislogin',
			'crlf'			=> "\r\n",
			'newline'		=> "\r\n",
			'charset'		=> 'utf-8',
			'mailtype'		=> 'html',

			'protocol'		=> 'smtp',
			'smtp_host'		=> 'mail.irislogin.it',
			'smtp_port'		=> '25',
			'smtp_timeout'	=> '10',
			'smtp_user'		=> 'no-reply@irislogin.it',
			'smtp_pass'		=> 'IRISLOGIN2012-no-login',

			'bcc_batch_mode'=> TRUE,
			'bcc_batch_size'=> '100'
			);
		break;
}


