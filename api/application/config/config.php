<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| Base Site URL
|
*/
$config['base_url']	= '';


if(strstr($_SERVER['HTTP_HOST'],'test') || strstr($_SERVER['HTTP_HOST'],'dev'))
{
	$config['log_threshold'] = 4;

	define('DEBUG', TRUE);
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}
else
{
	$config['log_threshold'] = 1;
	
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
	define('DEBUG', FALSE);
}

define('TESTING', FALSE);


/*
|--------------------------------------------------------------------------
| MailChimp settings
|--------------------------------------------------------------------------
*/

define('MAILCHIMP_URL', 'http://us4.api.mailchimp.com/1.3/?method=');
define('MAILCHIMP_API_KEY', '965cf13ff94369c948f24c977d8109d7-us4');
define('MAILCHIMP_LIST_ID', 'dcdd82847d');
define('MAILCHIMP_GROUPING_ID', '7321');


/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
*/
$config['uri_protocol']	= 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
*/

$config['url_suffix'] = '.html';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
*/
$config['language']	= 'italiano';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
*/
$config['enable_hooks'] = FALSE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
*/
$config['subclass_prefix'] = 'ESO_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-@';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
*/
$config['allow_get_array']		= TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger']	= 'c';
$config['function_trigger']		= 'm';
$config['directory_trigger']	= 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
*/
$config['log_date_format'] = 'Y-m-d H:i:s';
// $config['log_date_format'] = 'd-m-Y H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
*/
$config['encryption_key'] = 'irisLogin@QWERTY-350£170$';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
*/
$config['sess_cookie_name']		= 'irisLogin';
$config['sess_expiration']		= 7200;
$config['sess_expire_on_close']	= FALSE;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= TRUE;
$config['sess_table_name']		= 'user_saved_sessions';
$config['sess_match_ip']		= TRUE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update']	= 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
*/
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";
$config['cookie_secure']	= FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_token_iris_login';
$config['csrf_cookie_name'] = 'csrf_cookie_iris_login';
$config['csrf_expire'] = 3600;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
*/
$config['time_reference'] = 'local';
// Setting the correct TimeZone
// putenv("TZ=Europe/Rome");


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
*/
$config['rewrite_short_tags'] = FALSE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
*/
$config['proxy_ips'] = '';


/* End of file config.php */
/* Location: ./application/config/config.php */
