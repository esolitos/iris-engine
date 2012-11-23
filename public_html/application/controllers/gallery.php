<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller
{
	var $user = array();
	var $view_data = array();

	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();

		$this->load->model('gallery_model');
		
		$this->lang->load('error', 'italiano');
	}

	
	function index($w_id=FALSE)
	{
		if($w_id)
		{
			$this->view_data['website'] = $this->websites_model->get_info($w_id);
			$site_galleries = $this->gallery_model->get_galleries($w_id);
			
			if($site_galleries['success'])
			{
				$this->view_data['site_galleries'] = array();
				
				foreach($site_galleries['data'] as $gall)
				{
					$gallery = $this->gallery_model->get_gallery_images($gall->id);

					if($gallery['success'])
						$this->view_data['site_galleries'][$gall->name] = $gallery['data'];
					else
						log_message('error', "Error while loading gallery images. // W-ID: $w_id // G-ID: $gall->id //");
				}
			}
			else
			{
				$this->view_data['error'] = $this->lang->line($site_galleries['error']);
			}
			
			$this->load->view("gallery/galleries_view", $this->view_data);
		}
		else
		{
			show_404('gallery/index/'.$w_id);
		}
	}


	function single( $g_id = FALSE, $start_from = 0, $mode = 'include')
	{
		if($g_id)
		{
			$this->view_data['start_index'] = $start_from;
			$gallery = $this->gallery_model->load_gallery($g_id);

			if($gallery['success'])
				$this->view_data['gallery'] = $gallery['data'];
			else
				$this->view_data['error'] = $this->lang->line($gallery['error']);

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
		}
		else
		{
			show_404("gallery/single/$g_id");
		}
	}
	
}


/* End of file websites.php */
/* Location: ./application/controllers/admin/websites.php */