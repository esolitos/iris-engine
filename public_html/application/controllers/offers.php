<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offers extends ESO_Controller
{
	var $view_data;
	var $lang;
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('offers_model');
		$this->load->model('settings_model');
		$this->load->model('users_model');
		
		$this->view_data = init_viewdata();
		$this->view_data['css'] = FALSE;
		$this->view_data['is_admin'] = FALSE;
		$this->view_data['user'] = $this->session->all_userdata();
		$this->view_data['options'] = explode(',', $this->input->get('options'));
	}

	public function index($w_id=FALSE)
	{
		if(is_numeric($w_id))
		{
			$offers = $this->offers_model->get_offers($w_id, CURR_LANG_CODE, in_array('lang-strict', $this->view_data['options']));
			$this->view_data['offers'] = ($offers['status']) ? $offers['result'] : FALSE;
			$this->view_data['special_img'] = $this->websites_model->get_special_img($w_id);

			if(($style = $this->websites_model->get_style($w_id, SERVICE_ID_OFFERS)) != FALSE)
				$this->view_data['css'] = $style;

			$this->view_data['custom_style'] = $this->settings_model->getOptionArray($w_id, SERVICE_ID_OFFERS, array('text_color', 'title_color', 'bg_color'));

			$this->load->view('offers/offers_view', $this->view_data);
		}
		# Tutti gli altri casi non vanno bene!
		else
		{
			show_error("I'm not allowed to show you the offers if you do not select a website and your are not logged in!");
		}
	}
	
	public function view($o_id)
	{
		$offer_data = $this->offers_model->load_offer($o_id);
		
		if($offer_data['status'])
		{
			$this->view_data['offer'] = $offer_data['result'];

			if($offer_data['result']->expired OR ! $offer_data['result']->offer_visible)
			{
				show_404();
			}

			$this->view_data['css'] = $this->websites_model->get_style($offer_data['result']->website_id, SERVICE_ID_OFFERS);
			$this->view_data['custom_style'] = $this->settings_model->getOptionArray($offer_data['result']->website_id, SERVICE_ID_OFFERS, array('text_color', 'title_color', 'bg_color'));

			$this->view_data['return'] = $this->input->get('return');
			$this->view_data['special_img'] = $this->websites_model->get_special_img($offer_data['result']->website_id);
				
			
			$this->load->view('offers/offer_single_view', $this->view_data);
		}
		else
		{
			log_message('info', "Offer #{$o_id} not in the database.");
			show_404("offers/view/{$o_id}", FALSE);
		}
	}
	
	public function titles($w_id)
	{		
		$offers = $this->offers_model->get_offers_titles($w_id, 4, CURR_LANG_CODE, in_array('lang-strict', $this->view_data['options']));
		
		$this->view_data['offers'] = ($offers['status']) ? $offers['result'] : FALSE;
		$this->view_data['special_img'] = $this->websites_model->get_special_img($w_id);

		if(($style = $this->websites_model->get_style($w_id, SERVICE_ID_OFFERS)) != FALSE)
			$this->view_data['css'] = $style;

		$this->view_data['custom_style'] = $this->settings_model->getOptionArray($w_id, SERVICE_ID_OFFERS, array('text_color', 'title_color', 'bg_color'));

		$this->load->view('offers/offers_titles_view', $this->view_data);
		
	}
	

} 

/* End of file offers.php */
/* Location: ./application/controllers/offers.php */