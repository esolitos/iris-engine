<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gallery_Model extends CI_Model
{
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function add_gallery($w_id, $g_name)
	{
		$res = new Result();
		
		if($this->db->insert(TABLE_GALLERY, array('website_id' => $w_id, 'name' => $g_name)))
			return array(
				'success' => TRUE,
				'total' => $this->db->affected_rows(),
				'data' => array('gallery_id' => $this->db->insert_id()),
				);
			
		else
			return array(
				'success' => FALSE,
				'total' => $this->db->affected_rows(),
				'error' => "SRVC-GALL-ADD1",
				);
	}
	
	function del_gallery($g_id)
	{
		$res = new Result();
		
		$this->db->from(TABLE_GALLERY_IMAGES)->where('gallery_id', $g_id);
		$images_left = $this->db->count_all_results();
		if( $images_left > 0 )
		{
			return array(
				'success' => FALSE,
				'total' => $images_left,
				'error' => "SRVC-GALL-DEL1",
				);
		}
		else
		{
			if( $this->db->delete(TABLE_GALLERY, array('id' => $g_id)) )
				return array(
					'success' => TRUE,
					'total' => $this->db->affected_rows(),
					'data' => array(),
					);
			else
				return array(
					'success' => FALSE,
					'error' => "SRVC-GALL-DEL2",
					);
		}
	}

	function set_gallery_prop($g_id, array $prop)
	{
		$res = new Result();

		foreach ($prop as $key => $value)
			$this->db->set($key, $value);

		$this->db->where('id', $g_id);
		
		if($this->db->update(TABLE_GALLERY))
			return array(
				'success' => TRUE,
				'total' => $this->db->affected_rows(),	
				'data' => array(),
				);
		else
			return array(
				'success' => FALSE,
				'error' => "SRVC-GALL-SET",
				'total' => $this->db->affected_rows(),
				'data' => array(),
				);
	}
	
	function get_gallery_images($g_id, $add_path = PATH_WEB_GALLERY)
	{
		$res = new Result();

		$data = array();
		$total = 0;
		
		$this->db->where('gallery_id', $g_id);
		$query = $this->db->get(TABLE_GALLERY_IMAGES);
		
		if( ($total = $query->num_rows()) > 0 )
		{
			foreach ($query->result() as $row)
			{
				$row->full_size = "{$add_path}/{$g_id}/{$row->full_size}";
				$row->thumb = "{$add_path}/{$g_id}/{$row->thumb}";
				$data[] = $row;
			}
		}
		
		return array(
			'success' => TRUE,
			'total' => $total,
			'data' => $data,
			);
	}
	
	function get_galleries($w_id)
	{
		$res = new Result();
		
		$galleries = array();
		$total = 0;
		
		$this->db->where('website_id', $w_id);
		$query = $this->db->get(TABLE_GALLERY);
		
		if( ($total = $query->num_rows()) > 0 )
		{
			foreach ($query->result() as $row)
			{
				$row->num_images = $this->db->get_where(TABLE_GALLERY_IMAGES, array('gallery_id' => $row->id))->num_rows();
				$galleries[] = $row;
			}
		}
		
		return array(
			'success' => TRUE,
			'total' => $total,
			'data' => $galleries,
			);
		
		
	}
	
	function load_gallery($g_id)
	{
		$res = new Result();
		
		$data = array();

		$gallery_query = $this->db->get_where(TABLE_GALLERY, array('id' => $g_id));
		$images_query = $this->db->get_where(TABLE_GALLERY_IMAGES, array('gallery_id' => $g_id));
		
		if ($gallery_query->num_rows())
		{
			$gallery_data = $gallery_query->row();
			$gallery_data->images = array();

			foreach ($images_query->result() as $row)
			{
				$row->full_size = PATH_WEB_GALLERY."$g_id/$row->full_size";
				$row->thumb = PATH_WEB_GALLERY."$g_id/$row->thumb";
				$gallery_data->images[$row->id] = $row;
			}

			return array(
				'success' => TRUE,
				'total' => 1,
				'data' => $gallery_data,
				);
		}

		return  array(
			'success' => FALSE,
			'total' => $gallery_query->num_rows(),
			'error' => "SRVC-GALL-LOADEMPTY",
			);
		
	}
	
	function add_image($g_id, $image_full, $image_thumb, $img_title=NULL)
	{
		$res = new Result();

		if( ! file_exists(PATH_SRV_GALLERY."{$g_id}/{$image_full}") AND file_exists(PATH_SRV_GALLERY."{$g_id}/{$image_thumb}"))
		{
			return array(
				'success' => FALSE,
				'error' => "SRVC-GALL-ADDIMG1",
				);
		}
		else
		{
			if( ! $this->db->insert(TABLE_GALLERY_IMAGES, array('gallery_id'=>$g_id,  'full_size'=>$image_full, 'thumb'=>$image_thumb)))
			{
				return array(
					'success' => FALSE,
					'error' => "SRVC-GALL-ADDIMG2",
					);
			}
			else
				return array(
					'success' => TRUE,
					'data' => array(
						'image_id' => $this->db->insert_id(),
						),
					);
		}
	}
	
	function del_image($img_id)
	{
		$res = new Result();

		$img_data = $this->db->get_where(TABLE_GALLERY_IMAGES, array('id'=>$img_id));
		
		if(count($img_data->result()))
		{
			$this->db->delete(TABLE_GALLERY_IMAGES, array('id' => $img_id));

			return array(
				'success' => TRUE,
				'total' => $this->db->affected_rows(),
				'data' => array(
					'image_path' => PATH_SRV_GALLERY."{$img_data->row()->gallery_id}/{$img_data->row()->full_size}",
					'thumb_path' => PATH_SRV_GALLERY."{$img_data->row()->gallery_id}/{$img_data->row()->thumb}",
					),
				);
		}
		else
			return array(
				'success' => FALSE,
				'total' => count($img_data->result()),
				'error' => "SRVC-GALL-DELIMG"
				);
		
	}
	
	function check_space($id, $type = 'gallery')
	{
		switch ($type) {
			case 'image':
			case 'images':
				$this->db->where('gallery_id', $id)->from(TABLE_GALLERY_IMAGES);
				$num_images = $this->db->count_all_results();

				if($num_images >= GALLERY_MAX_GALLERY_IMAGES)
				{
					return array(
						'success' => FALSE,
						'total' => $this->db->affected_rows(),
						'error' => "SRVC-GALL-ADDIMG-LIMIT",
						);
				}
				else
				{
					return array(
						'success' => TRUE,
						'total' => $num_images,
						);
				}
				break;

			case 'gallery':
			default:
				$this->db->where('website_id', $id)->from(TABLE_GALLERY);
				$num_galleries = $this->db->count_all_results();
				if($num_galleries >= GALLERY_MAX_GALLERIES)
				{
					return array(
						'success' => FALSE,
						'total' => $num_galleries,
						'error' => "SRVC-GALL-ADD-LIMIT",
						);
				}
				else
				{
					return array(
						'success' => TRUE,
						'total' => $num_galleries,
						);
				}
				break;
		}
	}
}