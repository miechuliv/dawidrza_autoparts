<?php
class ModelToolImage extends Model {
	/**
	*	
	*	@param filename string
	*	@param width 
	*	@param height
	*	@param type char [default, w, h]
	*				default = scale with white space, 
	*				w = fill according to width, 
	*				h = fill according to height
	*	
	*/
	public function resize($filename, $width, $height, $type = "", $raw =false) {
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		
		$info = pathinfo($filename);
		
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type .'.' . $extension;
		
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		}

        if($raw)
        {
            return $new_image;
        }


		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

            return HTTPS_IMAGE . $new_image;
			//return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {

     
            return HTTP_IMAGE . $new_image;
		//	return $this->config->get('config_url') . 'image/' . $new_image;
		}	
	}

    // watermark extension
    function image_watermark($filename) {



        if ( (!file_exists(DIR_IMAGE . $filename)) && (!file_exists(DIR_IMAGE . $filename)) ) {
            return;
        }

        if (file_exists(DIR_IMAGE . $filename)) {
            $old_image = DIR_IMAGE . $filename;
        } else {
            $old_image = DIR_IMAGE . $filename;
        }

        $new_image = substr($filename, 0, strrpos($filename, '.')) . '-w.jpg';

        if (!file_exists(DIR_IMAGE . $new_image) || (filemtime($old_image) > filemtime(DIR_IMAGE . $new_image))) {
            $image = new Image($old_image);

            //miechu mod
          //  $watermark = $this->resize($this->config->get('config_logo'),300,130,'',true);
            $watermark = false;
            // miechu mod end

            $image->addwatermark('middle',$watermark);

            $image->save(DIR_IMAGE . $new_image);
        }

        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            return HTTPS_IMAGE . $new_image;
        } else {
            return HTTP_IMAGE . $new_image;
        }
    }
}
?>