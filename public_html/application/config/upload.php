<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['upload_path']	= PATH_SRV_UPLOAD;
$config['allowed_types'] = 'gif|jpg|jpeg|png|css';
$config['max_size']	= UPLOAD_MAX_SIZE;
$config['overwrite'] = FALSE;
$config['max_filename'] = '30';
$config['encrypt_name'] = FALSE;
$config['remove_spaces'] = TRUE;