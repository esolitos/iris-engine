<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends ESO_API_Controller
{
	var $user = array();
	var $view_data = array();

	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();

		$this->load->model('gallery_model');

		$this->view_data['base_url'] = base_url();
		// $this->view_data['options'] = explode(',', $this->input->get('options'));
	}

	
	function index($w_id=FALSE)
	{
		if($w_id)
		{
			// $this->view_data['website'] = $this->websites_model->get_info($w_id);
			$site_galleries = $this->gallery_model->get_galleries($w_id);

			if($site_galleries['success'])
			{
				foreach($site_galleries['data'] as $gall)
				{
					$gallery = $this->gallery_model->get_gallery_images($gall->id);

					if($gallery['success'])
					{
						$this->view_data['galleries'][] = array(
							'gid'=>$gall->id,
							'name'=>$gall->name,
							'images'=>$gallery['data']
						);
					}
					else
						log_message('error', "Error while loading gallery images. // W-ID: $w_id // G-ID: $gall->id //");
				}
			}
			else
			{
				$this->view_data['error'] = $site_galleries['error'];
			}
			
			$this->ret(TRUE, $this->view_data);
		}
		else
		{
			log_message('warning', "Gallery: Website id not passed by url." );
			$this->ret('FALSE', array());
		}
	}


	function single( $g_id = FALSE, $start_from = 0)
	{
		if($g_id)
		{
			$this->view_data['start_index'] = $start_from;
			$gallery = $this->gallery_model->load_gallery($g_id);

			if($gallery['success'])
			{
				$this->view_data['gallery'] = $gallery['data'];
			}	
			else
				$this->view_data['error'] = $gallery['error'];
			
			$this->ret(TRUE, $this->view_data);

/*
			switch ($mode)
			{
				case 'fullscreen':
				case 'standalone':
					$this->load->view("gallery/single_gallery_view_standalone", $this->view_data);
					break;

				case 'include':
				default:
					$this->load->view("gallery/single_gallery_view", $this->view_data);
					break;
			}
*/

		}
		else
		{
			log_message('warning', "Gallery: Gallery id not passed by url." );
			$this->ret('FALSE', array());
		}
	}
	
}


/* End of file websites.php */
/* Location: ./application/controllers/gallery.php */
