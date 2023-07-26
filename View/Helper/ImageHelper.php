<?php 
/* Helper to auto resize images, cache them and display them as called */
App::uses('AppHelper', 'View/Helper');

class ImageHelper extends AppHelper{
	public $helpers = array('Html');

    /*
     * Array of all available image types
     */
    var $type = array(
        1 => 'gif',
        2 => 'jpg',
        3 => 'png'
    );
    
    /*
     * Tmp folder location for thumbs
     */
    public $tmpLocation = null;

	/*
     * Tmp folder location for resize
     */
    public $tmpResizeLocation = null;
    
    /* Construct
     * Link $tmpLocation to the appropriate location as well as check if folder exists and is writable, if not
     * create folder and change permissions
     * 
     */
    function __construct(View $View, $settings = array()){
        parent::__construct($View, $settings);
    }
    
    
    /* Crop
     * crop image passed through, if no image is passed return false 
     * 
     */
    
    function crop($obj = null, $folder=null, $width = 100, $height = 100, $options=array()) {
        $file = WWW_ROOT . $folder. $obj;
        $name = substr($obj,0,-4);
		
		$dir = WWW_ROOT . $folder . DS . 'cache' . DS . 'thumbs' . DS;
        if(!is_dir($dir)) mkdir($dir, 0777, true);
        $this->tmpLocation=$dir;
            
        // assure that file exists
        if(is_file($file)){
            
            list($w, $h, $type) = getimagesize($file);
            // if the file is an image and not a swf or undetermined file
            if($type){
                
                $name .= '_'.$width . 'x' . $height;
                // get file ext for ease of use
                $fileType = $this->type[$type];
                $name.='.'.$fileType;
                    
				// check that file does not exist, if it does return image otherwise proceed
                if($this->checkFile($this->tmpLocation . $name)){
                
                    //loop through file type and prepare image for cropping
                    switch($fileType) {
                        case 'gif':
                            $img = imagecreatefromgif($file);
                            break;
                        case 'jpg':
                            $img = imagecreatefromjpeg($file);
                            break;
                        case 'png':
                            $img = imagecreatefrompng($file);
                            break;
                    }
                    
                    // determine larger side and size both appropriately
                    if($w > $h){
                        if($width > $height){
                            $ratio = $h/$width;
                        } else {
                            $ratio = $h/$height;
                        }
                    } else {
                        if($width > $height){
                            $ratio = $w/$width;
                        } else {
                            $ratio = $w/$height;
                        }
                    }
                    $new_width = round($w/$ratio);
                    $new_height = round($h/$ratio);
                    
                    // determine how far in to middle the crop should begin
                    $src_x = ($new_width - $width) / 2;
                    $src_y = ($new_height - $height) / 2;
                    
                    // create thumb placeholder and then create image
                    $thumb = imagecreatetruecolor($width, $height);
                    imagecopyresized($thumb, $img, 0, 0, $src_x, $src_y, $new_width, $new_height, $w, $h);
                    
                    imagejpeg($thumb, $this->tmpLocation . $name, 100);
                    
                }
				return $this->Html->image('/'.$folder . 'cache/thumbs/' . $name, $options);
                
            } else {
                $fileType = substr($file, strrpos($file, '.') + 1);
                return 'There is no preview for file ' . $name;
            }
        } else {
            return false;
        }
    }
    
	/* Resize
     * resize image passed through, if no image is passed return false 
     * 
     */
    
    function resize($obj = null, $folder=null, $wh='width', $val=400, $options=array()) {
        $file = WWW_ROOT . $folder . $obj;
        //$name = substr($obj, strrpos($obj, '/') + 1);
		$name=$obj;

		$dir = WWW_ROOT . $folder . DS . 'cache' . DS . 'resize' . DS;
        if(!is_dir($dir)) mkdir($dir, 0777, true);
		$this->tmpResizeLocation=$dir;

        // assure that file exists
        if(is_file($file)){
            
            list($w, $h, $type) = getimagesize($file);
            // if the file is an image and not a swf or undetermined file
            if($type){
                
				// check that file does not exist, if it does return image otherwise proceed
                if($this->checkFile($this->tmpResizeLocation . $name)){
                
                    // get file ext for ease of use
                    $fileType = $this->type[$type];
                    
                    //loop through file type and prepare image for cropping
                    switch($fileType) {
                        case 'gif':
                            $img = imagecreatefromgif($file);
                            break;
                        case 'jpg':
                            $img = imagecreatefromjpeg($file);
                            break;
                        case 'png':
                            $img = imagecreatefrompng($file);
                            break;
                    }

					if ($wh=='width'){
						$width=$val;
						$height=($width*$h)/$w;
					}
					else {
						$height=$val;
						$width=($height*$w)/$h;
					}
                    
                    // create thumb resize placeholder and then create image
                    $thumb = imagecreatetruecolor($width, $height);
                    imagecopyresized($thumb, $img, 0, 0, 0, 0, $width, $height, $w, $h);
                    
                    imagejpeg($thumb, $this->tmpResizeLocation . $name, 100);
                    
                }
				return $this->Html->image('/'. $folder . 'cache/resize/' . $name, $options);
                
            } else {
                $fileType = substr($file, strrpos($file, '.') + 1);
                return 'There is no preview for file ' . $name;
            }
        } else {
            return false;
        }
    }

    /* Check File
     * Check if file exists, if it does NOT then return true, else, return false
     * 
     */
    
    function checkFile($name){
        if(is_file($name)){
            return false;
        } else {
            return true;
        }
    }
    
}
