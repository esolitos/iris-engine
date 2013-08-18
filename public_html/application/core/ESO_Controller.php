<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ESO_Controller extends CI_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{	
		parent::__construct();
		
		if( isset($_REQUEST['lang']) )
		{
			$new_lang = $this->_getLanguage($_GET['lang']);
			$this->session->set_flashdata('user_language', $new_lang);
			define('CURR_LANG', $new_lang);
			define('CURR_LANG_CODE', $this->_getLanguageCode($new_lang));
			
			log_message('debug', "Language from REQUEST: {$new_lang}");
		}
		else if ( ($user_language = $this->session->flashdata('user_language')) )
		{
			$this->session->keep_flashdata('user_language');
			define('CURR_LANG', $user_language);
			define('CURR_LANG_CODE', $this->_getLanguageCode($user_language));
			
			log_message('debug', "Language from SESSION: {$user_language}");
		}
		else
		{
			$default_language = $this->_getLanguage($this->config->item('language'));
			define('CURR_LANG', $default_language);
			define('CURR_LANG_CODE', $this->_getLanguageCode($default_language));
			
			log_message('debug', "Language from DEFAULTS: {$default_language}");
		}


		
		log_message('debug', "Extended Controller Class Initialized");	
	}
	
	private function _getLanguage($value='it')
	{
		switch ($value) {
			case 'en':
			case 'eng':
			case 'english':
				return 'english';

			case 'de':
			case 'deu':
			case 'german':
			case 'deutsche':
				return 'deutsche';

			case 'it':
			case 'ita':
			case 'italian':
			case 'italiano':
			default:
				return 'italiano';
		}
		
	}
	private function _getLanguageCode($value)
	{
		switch ($value) {
			case 'english':
				return LANG_ENG;

			case 'deutsche':
				return LANG_DEU;

			case 'italiano':
				return LANG_ITA;
				
			default:
				return LANG_DEFAULT;
		}
		
	}

}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/ESO_Controller.php */