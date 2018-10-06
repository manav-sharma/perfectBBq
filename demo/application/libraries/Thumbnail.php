<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Thumbnail {

  var $save_to_file = true;
  var $image_type = -1;
  var $quality = 100;
  var $max_x = 100;
  var $max_y = 100;
  var $cut_x = 0;
  var $cut_y = 0;
  var $rgbColor = "#FFFFFF";
 
	public function __construct() {
    	$this->CI = &get_instance();
	}
 
  public function SaveImage($im, $filename) {
 
    $res = null;
 
    // ImageGIF is not included into some GD2 releases, so it might not work
    // output png if gifs are not supported
    if(($this->image_type == 1)  && !function_exists('imagegif')) $this->image_type = 3;

    switch ($this->image_type) {
      case 1:
        if ($this->save_to_file) {
          $res = ImageGIF($im,$filename);
        }
        else {
          header("Content-type: image/gif");
          $res = ImageGIF($im);
        }
        break;
      case 2:
        if ($this->save_to_file) {
          $res = ImageJPEG($im,$filename,$this->quality);
        }
        else {
          header("Content-type: image/jpeg");
          $res = ImageJPEG($im, NULL, $this->quality);
        }
        break;
      case 3:
        if (PHP_VERSION >= '5.1.2') {
          // Convert to PNG quality.
          // PNG quality: 0 (best quality, bigger file) to 9 (worst quality, smaller file)
          $quality = 9 - min( round($this->quality / 10), 9 );
          if ($this->save_to_file) {
            $res = ImagePNG($im, $filename, $quality);
          }
          else {
            header("Content-type: image/png");
            $res = ImagePNG($im, NULL, $quality);
          }
        }
        else {
          if ($this->save_to_file) {
            $res = ImagePNG($im, $filename);
          }
          else {
            header("Content-type: image/png");
            $res = ImagePNG($im);
          }
        }
        break;
    }
 
    return $res;
 
  }
 
  public function ImageCreateFromType($type,$filename) {
   $im = null;
   switch ($type) {
     case 1:
       $im = ImageCreateFromGif($filename);
       break;
     case 2:
       $im = ImageCreateFromJpeg($filename);
       break;
     case 3:
       $im = ImageCreateFromPNG($filename);
       break;
    }
    return $im;
  }
 
  // generate thumb from image and save it
  public function GenerateThumbFile($from_name, $to_name) {
    // if src is URL then download file first
    $temp = false;
    if (substr($from_name,0,7) == 'http://') {
      $tmpfname = tempnam("tmp/", "TmP-");
      $temp = @fopen($tmpfname, "w");
      if ($temp) {
        @fwrite($temp, @file_get_contents($from_name)) or die("Cannot download image");
        @fclose($temp);
        $from_name = $tmpfname;
      }
      else {
        die("Cannot create temp file");
      }
    }

    // check if file exists
    if (!file_exists($from_name)) die("Source image does not exist!");
    
    // get source image size (width/height/type)
    // orig_img_type 1 = GIF, 2 = JPG, 3 = PNG
    list($orig_x, $orig_y, $orig_img_type, $img_sizes) = @GetImageSize($from_name);

    // cut image if specified by user
    if ($this->cut_x > 0) $orig_x = min($this->cut_x, $orig_x);
    if ($this->cut_y > 0) $orig_y = min($this->cut_y, $orig_y);
 
	$maxWidth = $this->max_x;
  	$maxHeight = $this->max_y;
  
    // should we override thumb image type?
    $this->image_type = ($this->image_type != -1 ? $this->image_type : $orig_img_type);
 
    // check for allowed image types
    if ($orig_img_type < 1 or $orig_img_type > 3) die("Image type not supported");
    if ($orig_x > $this->max_x or $orig_y > $this->max_y) {

		//echo $_GET['m']; die;
      // resize
	  if((empty($this->max_y) or $this->max_y == 0) and (empty($this->max_x) or $this->max_x == 0))
	  {
		  $per_y = 1;
		  $per_x = 1;
	  }
	  elseif(empty($this->max_y) or $this->max_y == 0)
	  {
      	$per_x = $orig_x / $this->max_x;
		$per_y = $per_x;
	  }
	  elseif(empty($this->max_x) or $this->max_x == 0)
	  {
      	$per_y = $orig_y / $this->max_y;
		$per_x = $per_y;
	  }
	  else
	  {
		  $per_x = $orig_x / $this->max_x;
		  $per_y = $orig_y / $this->max_y;		  
	  }
	  if ($per_y > $per_x) {
		$this->max_x;
	  }
	  elseif ($per_y == $per_x) {
		$this->max_x;
		$this->max_y; 
	  }
	  else {
		$this->max_y;
	  }
    }
    else {
      // keep original sizes, i.e. just copy
      if ($this->save_to_file) {
        @copy($from_name, $to_name);
      }
      else {
        switch ($this->image_type) {
          case 1:
              header("Content-type: image/gif");
              readfile($from_name);
            break;
          case 2:
              header("Content-type: image/jpeg");
              readfile($from_name);
            break;
          case 3:
              header("Content-type: image/png");
              readfile($from_name);
            break;
        }
      }
      return;
    }
 
    if ($this->image_type == 1) {
      // should use this function for gifs (gifs are palette images)
      $ni = imagecreate($maxWidth, $maxHeight);
    }
    else {
      // Create a new true color image
      $ni = ImageCreateTrueColor($maxWidth,$maxHeight);
    }
 
    // Fill image with white background (255,255,255)
	
	$rColor = 255;
	$gColor = 255;
	$bColor = 255;
	$rgbColor = $this->rgbColor;
	if(!empty($rgbColor ))
	{
		$rgbColor = str_replace('#','',$rgbColor);
		$rColor = (hexdec(substr($rgbColor,0,2)) > 255)?255:hexdec(substr($rgbColor,0,2));
		$gColor = (hexdec(substr($rgbColor,2,2)) > 255)?255:hexdec(substr($rgbColor,2,2));
		$bColor = (hexdec(substr($rgbColor,4,2)) > 255)?255:hexdec(substr($rgbColor,4,2));
	}
	
    $white = imagecolorallocate($ni, $rColor, $gColor, $bColor);
    imagefilledrectangle( $ni, 0, 0, $maxWidth, $maxHeight, $white);
    // Create a new image from source file
    $im = $this->ImageCreateFromType($orig_img_type,$from_name);
    // Copy the palette from one image to another
    imagepalettecopy($ni,$im);
    // Copy and resize part of an image with resampling
	
	$xCodination = round(($maxWidth - $this->max_x)/2);
	$yCodination = round(($maxHeight - $this->max_y)/2);
	
	imagecopyresampled(
      $ni, $im,             // destination, source
      $xCodination, $yCodination, 0, 0,           // dstX, dstY, srcX, srcY
      $this->max_x, $this->max_y,       // dstW, dstH
      $orig_x, $orig_y);    // srcW, srcH
 
    // save thumb file
    $this->SaveImage($ni, $to_name);

    if($temp) {
      unlink($tmpfname); // this removes the file
    }

  }


public function generateThumbImage($originalImgURL,$thumbImgURL,$thumbWidth='',$thumbHeight='',$imgQuality = 100,$rgbColor='#FFFFFF')
{
	
	$originalImgURL = urldecode($originalImgURL);
	$thumbImgURL	= urldecode($thumbImgURL);
	if(intval($thumbWidth) === 0) 
		$thumbWidth = '';
	if(intval($thumbHeight) === 0)
		$thumbHeight = '';
	
	if (!file_exists($originalImgURL)) die('Images does not exist.');
		
	//$img = new thumbnail;
	
	// initialize
	$this->max_x        = $thumbWidth;
	$this->max_y        = $thumbHeight;
	$this->cut_x        = 0;
	$this->cut_y        = 0;
	$this->quality      = $imgQuality;
	$this->save_to_file = true;
	$this->image_type   = -1;
	$this->rgbColor     = $rgbColor;
	
	// generate thumbnail
	$this->GenerateThumbFile($originalImgURL, $thumbImgURL);
}
}
//generateThumbImage("images/Avatar_Eclipse.jpg","thumbs/test10.jpg",100,100,100);
?>