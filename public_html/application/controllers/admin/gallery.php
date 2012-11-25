<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller
{
	var $view_data = array();
	var $head_data = array();

	function __construct()
	{
		parent::__construct();
		$this->view_data = init_viewdata();
		$this->head_data = init_headdata();
		
		
		if( ! $this->session->userdata('logged_in'))
		{
			redirect("admin/login.html?dest=admin/gallery");
			exit();
		}

		$this->view_data['user'] = $this->session->all_userdata();
		
		$this->load->model('gallery_model');		
	}
		
	function index()
	{
		$this->view_data['form_hidden'] = array('website_id'=>$this->view_data['user']['user_website']);

		$this->view_data['galleries'] = $this->gallery_model->get_galleries($this->view_data['user']['user_website']);
		$this->view_data['css_file'] = $this->websites_model->get_style($this->view_data['user']['user_website'], SERVICE_ID_GALLERY);
		
		if( ! $this->view_data['galleries']['success'])
		{
			$this->view_data['galleries']['total'] = FALSE;
			$this->view_data['error'] = $this->lang->line($this->view_data['galleries']['error']);
		}
		
		output(array(
				'header' => $this->head_data,
				'footer' => TRUE,
				'gallery/admin_view' => $this->view_data
				));
	}
	
	
	function manage($g_id)
	{
		if(($g_title = $this->input->post('gallery_title')) != FALSE)
		{
			$result = $this->gallery_model->set_gallery_prop($g_id, array('name' => $g_title));
			if($result['success'])
				print json_encode(array('success'=>TRUE));
			else
				print json_encode(array('success'=>FALSE));
		}
		else
		{
			$gallery = $this->gallery_model->load_gallery($g_id);

			if( ! $gallery['success'] )
				$this->view_data['error'] = $this->lang->line($gallery['error']);
			else
				$this->view_data['gallery_data'] = $gallery['data'];
			
			output(array(
					'header' => $this->head_data,
					'footer' => TRUE,
					'gallery/admin_manage_view' => $this->view_data
					));
		}
	}
	
	function add()
	{
		if(($g_title = $this->input->post('gallery_title')) != FALSE)
		{
			$images = $this->input->post('images');
			$gallery = $this->gallery_model->add_gallery($this->view_data['user']['user_website'], $g_title);
			if($gallery['success'])
			{
				$g_id = $gallery['data']['gallery_id'];
				$gallery_path = PATH_SRV_GALLERY.$g_id.'/';
				$tmp_path = PATH_SRV_GALLERY.'TEMP/';
				
				if(mkdir($gallery_path, 0775))
				{
					foreach ($images as $img)
					{
						$img_file = pathinfo($img);
						$img_thumb = $img_file['filename']."-SMALL.".$img_file['extension'];
						
						if( ! rename($tmp_path.$img, $gallery_path.$img) OR ! rename($tmp_path.$img_thumb, $gallery_path.$img_thumb))
						{
							$this->view_data['error'] = "Alcune immagini non sono state caricate correttamente. Controlla che la gallery funzioni correttamente.";
							break;
						}
						else
						{
							$result = $this->gallery_model->add_image($g_id, $img, $img_thumb);

							if( ! $result['success'] )
							{
								$this->view_data['error'] = $this->lang->line($result['error']);
								break;
							}
						}	
					} //foreach img	
					
					$this->view_data['message'] = "Galleria Aggiunta!";
					$this->index();
					return;
				}
			}

				$this->view_data['error'] = "Impossibile creare una galleria.";
				$this->index();
		}
		else
			show_error("Accesso Negato!");
	}
	
	function delete($g_id)
	{
		$gallery_images = $this->gallery_model->get_gallery_images($g_id, PATH_SRV_GALLERY);
		
		foreach($gallery_images['data'] as $image)
		{
			unlink($image->full_size);
			unlink($image->thumb);
			$this->gallery_model->del_image($image->id);
		}
		$this->gallery_model->del_gallery($g_id);
		
		$this->view_data['message'] = "Galleria Rimossa Correttamente";
		$this->index();
		
	}
	
	
	function file($action)
	{
		switch ($action) {
			case 'upload':
				$this->_file_upload();
				break;
			
			case 'delete':
				$this->_file_delete();
				break;
			
			default:
				show_404("admin/gallery/file/$action");
				break;
		}
	}
	
	
	private function _file_upload()
	{
		if($this->input->post("website_id"))
		{
			$result = array(
				'error'=>"",
				'msg'=>"",
				);

			$file_element_name = 'image_upload';

			if ( ! ($g_id = $this->input->post('gallery_id')) )
				$result['error'] .= "Gallery ID not valid.\n";


			if (!$result['error'])
			{
				$gall_id = $this->input->post('gallery_id');
				if($gall_id == 'new')
				{
					$upload_config['upload_path'] = PATH_SRV_GALLERY."TEMP/";
					$result['thumb_path'] =  PATH_WEB_GALLERY."TEMP/";
				}
				else
				{
					mkdir_if(PATH_SRV_GALLERY."$g_id/");

					$upload_config['upload_path'] = PATH_SRV_GALLERY."$g_id/";
					$result['thumb_path'] =  PATH_WEB_GALLERY."$g_id/";
				}

				$upload_config['allowed_types'] = 'jpg|png|jpeg';
				$upload_config['max_size'] = UPLOAD_MAX_SIZE; //2MB
				$upload_config['encrypt_name'] = TRUE;
				$this->load->library('upload', $upload_config);

				if ( ! $this->upload->do_upload($file_element_name))
				{
					$result['error'] .= $this->upload->display_errors('','');
				}
				else
				{
					$result['msg'] .= "Upload Success!";

					$image_data = $this->upload->data();
					$thumb_name = $image_data['raw_name']."-SMALL".$image_data['file_ext'];
					
					$result['file_name'] =  $image_data['file_name'];
					$result['thumb_path'] .=  $thumb_name;

					$image_config['source_image'] = $image_data['full_path'];
					$image_config['create_thumb'] = FALSE;
					$image_config['height'] = "1024";
					$image_config['width'] = "1280";
					$image_config['maintain_ratio'] = TRUE;
					$image_config['master_dim'] = 'auto';
					
					$this->load->library('image_lib', $image_config);
					$this->image_lib->resize();


					$thumb_config['source_image'] = $image_data['full_path'];
					$thumb_config['create_thumb'] = TRUE;
					$thumb_config['thumb_marker'] = "-SMALL";
					$thumb_config['height'] = "50";
					$thumb_config['width'] = "50";
					$thumb_config['maintain_ratio'] = TRUE;
					$thumb_config['x_axis'] = 0;
					$thumb_config['y_axis'] = 0;

					if($image_data['image_width'] > $image_data['image_height']) //Immagine larga
					{
						$thumb_config['master_dim'] = 'height';
						$this->image_lib->initialize($thumb_config);
						$this->image_lib->resize();
					}
					else
					{
						$thumb_config['master_dim'] = 'width';
						$this->image_lib->initialize($thumb_config);
						$this->image_lib->resize();
					}
					
					$thumb_config['source_image'] = $upload_config['upload_path'].$thumb_name;
					$thumb_config['create_thumb'] = FALSE;
					$thumb_config['maintain_ratio'] = FALSE;
					
					$this->image_lib->initialize($thumb_config);
					$this->image_lib->crop();
					

					
					if($gall_id != 'new')
					{
						$db_insert = $this->gallery_model->add_image($gall_id, $result['file_name'], $thumb_name);
						if($db_insert['success'])
							$result['img_id'] = $db_insert['data']['image_id'];
						else
							$result['error'] .= "Impossibile aggiungere immagine al database";
					}
				}

				//for security reason, we force to remove all uploaded file
				@unlink($_FILES[$file_element_name]);
			}

			echo json_encode($result);
		}
		else
			show_error('Accesso Negato!');
	}


	private function _file_delete()
	{
		if($this->input->is_ajax_request())
		{
			$result = array(
				'msg' => "",
				'error' => "",
				);
			$w_id = $this->input->post('website_id');
			$g_id = $this->input->post('gallery_id');
			$img_id = $this->input->post('image_id');
			
			if($w_id AND $g_id AND $img_id)
			{
				$del_result = $this->gallery_model->del_image($img_id);
				if($del_result['success'])
				{
					unlink($del_result['data']['image_path']);
					unlink($del_result['data']['thumb_path']);
					
					$result['msg'] = "Immagine Rimossa Correttamente";
				}
				else
				{
					$result['error'] .= "Impossibile eliminare immagine. Errore Database.";
				}
			}
			else
			{
				$result['error'] .= "Parametri non Corretti!";
			}
			
			print json_encode($result);
		}
		else
			show_error("Accesso Negato!");
	}

}


/* End of file gallery.php */
/* Location: ./application/controllers/admin/gallery.php */