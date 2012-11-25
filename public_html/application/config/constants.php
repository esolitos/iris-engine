<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| User defined Constants
|--------------------------------------------------------------------------
*/

// 24 in Seconds (for timestamp tests)
define('H24_TIMESTAMP', (24 * 60 * 60));


//  Impostando questo valore definiamo il numero di gioni dalla scadenza in cui la sottoscrizione prende lo status di "In Scadenza". (default: 30)
define('IS_EXPIRING_STATUS_DAYS', 45);

// Imposta il numero massimo di indirizzi email a cui è possibile inviare i test. Per evitare che usino i test come newsletter.
define('NEWSLETTER_MAX_TEST_MAILS', 3);
define('NEWSLETTER_MAX_TEST', 2);

// Massima dimensione disponibile per l'upload (5MB)
define('UPLOAD_MAX_SIZE', (1024 * 5));


// Defaults Values
define('IRIS_MAIL', 'iris@irislogin.it');
define('IRIS_MAIL_SUPPORT', 'iris@irislogin.it');
define('IRIS_MAIL_TECH', 'iris@irislogin.it');
define('ENGINE_MAIL', 'engine@irislogin.it');

define('DEFAUT_PASSWORD', 'IRISLOGIN-welcome-pass');
define('STYLE_DEFAULT_FILE', "/public/css/defaults/services-style.min.css");

// SERVER Path
define('PATH_SRV_UPLOAD', FCPATH.'public/upload/');
define('PATH_SRV_GALLERY', PATH_SRV_UPLOAD.'gallery/');

// WEB Paths
define('PATH_WEB_UPLOAD', '/public/upload/');
define('PATH_WEB_GALLERY', PATH_WEB_UPLOAD.'gallery/');
define('PATH_WEB_CSS', 'public/css/');

// Master settings
define('USER_ID_MASTER_ADMIN', 1);
define('WEBSITE_ID_IRIS', 1);

// DB Tables
define('TABLE_WEBSITES', 'websites');
define('TABLE_STYLE', 'styles');

define('TABLE_OPTIONS_NAMES', 'settings_type');
define('TABLE_OPTIONS', 'settings');

define('TABLE_SERVICES', 'services');
define('TABLE_SUBSCRIPTIONS', 'subscription');

define('TABLE_RESERVATIONS', 'srvc_booking');
define('TABLE_OFFERS', 'srvc_offers');
define('TABLE_NEWSLETTER', 'srvc_newsletter');
define('TABLE_GALLERY', 'srvc_gallery');
define('TABLE_GALLERY_IMAGES', 'srvc_gallery_images');

/*
	TODO Trovare una soluzione alternativa e dinamica per i nomi dei servizi!
*/

define('SERVICE_ID_OFFERS', 1);
define('SERVICE_NAME_OFFERS', 'offers');

define('SERVICE_ID_BOOKING', 2);
define('SERVICE_NAME_BOOKING', 'booking');

define('SERVICE_ID_NEWSLETTER', 3);
define('SERVICE_NAME_NEWSLETTER', 'newsletter');

define('SERVICE_ID_GALLERY', 4);
define('SERVICE_NAME_GALLERY', 'gallery');


// Poiche' le password sono genberate dipendenti dal server necessitiamo di piu tabelle.
switch (TRUE) {
	case (strstr($_SERVER['HTTP_HOST'],'.it')):
		define('TABLE_USERS', 'users_iris');
		break;
	
	case (strstr($_SERVER['HTTP_HOST'],'.dev')):
	default:
		define('TABLE_USERS', 'users');
		break;
}

/* End of file constants.php */
/* Location: ./application/config/constants.php */