<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
*/

$active_record = TRUE;
$active_group = 'production';

switch (TRUE) {
	case (strstr($_SERVER['HTTP_HOST'],'.dev')):
	case (strstr($_SERVER['HTTP_HOST'],'esolitos.com')):
		$active_group = 'dev';
		break;

	case (strstr($_SERVER['HTTP_HOST'],'test')):
		$active_group = 'testing';
		break;


	case (strstr($_SERVER['HTTP_HOST'],'demo')):
		$active_group = 'demo';
		break;
	
	default:
		$active_group = 'production';
		break;
}

// Local DEV
$db['dev']['hostname'] = 'localhost';
$db['dev']['username'] = 'iris_offerEngine';
$db['dev']['password'] = '1r1s_offer';
$db['dev']['database'] = 'iris_offerEngine';
$db['dev']['dbdriver'] = 'mysql';
$db['dev']['dbprefix'] = '';
$db['dev']['pconnect'] = TRUE;
$db['dev']['db_debug'] = TRUE;
$db['dev']['cache_on'] = FALSE;
$db['dev']['cachedir'] = '';
$db['dev']['char_set'] = 'utf8';
$db['dev']['dbcollat'] = 'utf8_general_ci';
$db['dev']['swap_pre'] = '';
$db['dev']['autoinit'] = TRUE;
$db['dev']['stricton'] = TRUE;


// Online TESTING
// Copio tutti i parametri dalla DEV e cambio solo il db.
$db['testing'] = $db['dev'];
$db['testing']['username'] = 'iris_test';
$db['testing']['password'] = 'U<:])987N^n4i6c';
$db['testing']['database'] = 'irislogin_testing';

// Online - Production
$db['production']['hostname'] = 'localhost';
$db['production']['username'] = 'iris_master';
$db['production']['password'] = 'Q{r;7b9@(^6Y32e';
$db['production']['database'] = 'irislogin_main';
$db['production']['dbdriver'] = 'mysql';
$db['production']['dbprefix'] = '';
$db['production']['pconnect'] = TRUE;
$db['production']['db_debug'] = FALSE;
$db['production']['cache_on'] = TRUE;
$db['production']['cachedir'] = '';
$db['production']['char_set'] = 'utf8';
$db['production']['dbcollat'] = 'utf8_general_ci';
$db['production']['swap_pre'] = '';
$db['production']['autoinit'] = TRUE;
$db['production']['stricton'] = TRUE;

// Online DEMO
// Copio tutti i parametri dalla PRODUCTION e cambio solo il db.
$db['demo'] = $db['production'];
$db['demo']['username'] = 'iris_demo';
$db['demo']['password'] = 'speb0lelo4wa';
$db['demo']['database'] = 'irislogin_demo';


/* End of file database.php */
/* Location: ./application/config/database.php */