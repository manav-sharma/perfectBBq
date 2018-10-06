<?php

class Cms_mfm{

    function showtitle($val)
    {
    	global $titlelist;
    	echo ($titlelist[$val] ? $titlelist[$val] . " " : "") . "Library";
    }
    
    function currentpath($val)
    {
    	$filespath = array('image'=>'images','advvideo'=>'media/video','advaudio'=>'media/audio','advfile'=>'documents');
    	return $filespath[$val] ? $filespath[$val] : "";
    }
    	
    //a last step above to filter out "." and ".." segments from the returned URL's path
    function url_remove_dot_segments( $path )
    {
        // multi-byte character explode
        $inSegs  = preg_split( '!/!u', $path );
        $outSegs = array( );
        foreach ( $inSegs as $seg )
        {
            if ( $seg == '' || $seg == '.')
                continue;
            if ( $seg == '..' )
                array_pop( $outSegs );
            else
                array_push( $outSegs, $seg );
        }
        $outPath = implode( '/', $outSegs );
        if ( $path[0] == '/' )
            $outPath = '/' . $outPath;
        // compare last multi-byte character against '/'
        if ( $outPath != '/' && (mb_strlen($path)-1) == mb_strrpos( $path, '/', 'UTF-8' ) )
            $outPath .= '/';
        return $outPath;
    }
    
    //replaces special characters for latvian and russian lang., and removes all other
    function format_filename($filename) {
    	$bads = array(' ','a','c','e','g','i','k','l','n','r','�','u','�','A','C','E','G','I','K','L','N','R','�','U','�','$','&','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','??','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','?','??','?','?','?','?','?','?');
    	$good = array('-','a','c','e','g','i','k','l','n','r','s','u','z','A','C','E','G','I','K','L','N','R','S','U','Z','s','and','A','B','V','G','D','E','J','Z','Z','I','J','K','L','M','N','O','P','R','S','T','U','F','H','C','C','S','S','T','T','E','Ju','Ja','a','b','v','g','d','e','e','z','z','i','j','k','l','m','n','o','p','r','s','t','u','f','h','c','c','s','t','t','y','z','e','ju','ja');
    	$filename = str_replace($bads,$good,trim($filename));
    	$allowed = "/[^a-z0-9\\.\\-\\_\\\\]/i";
    	$filename = preg_replace($allowed,'',$filename);
    	return $filename;
    }
    
    //convert file size to human readable format
    function byte_convert($bytes) {
      $symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
      $exp = 0;
      $converted_value = 0;
      if( $bytes > 0 ) {
        $exp = floor( log($bytes)/log(1024) );
        $converted_value = ( $bytes/pow(1024,floor($exp)) );
      }
      return sprintf( '%.2f '.$symbol[$exp], $converted_value );
    }
    
    //show recursive directory tree
    function print_tree($dir = '.') {
    	global $root_path;
      echo '<ul class="dirlist">';
      $d = opendir($dir);
      while($f = readdir($d)) {
        if(strpos($f, '.') === 0) continue;
        $ff = $dir . '/' . $f;
        if(is_dir($ff)) {
      	  echo '<li><a href="' . $root_path . '/' . $ff . '/" onclick="currentdirectory(\'mfm?viewdir=' . $ff . '\',\'view-files\',this); return false;">' . $f . '</a>';
    			$this->print_tree($ff);
      	  echo '</li>';
    		}
      }
      echo '</ul>';
    }
    
    //show file list of given directory
    function print_files($c = '.') {
        
    	global $root_path, $mode, $thmb_size, $file_class, $lng;
        $mode = $_GET['type'];
        $thmb_size = 100;       	//max size of preview thumbnail
        $no_script = false;       //true/false - turns scripts into text files
        $lang = 'en';           	//language (look in /mfm/lang/ for available)
        error_reporting(E_ALL);				//'E_ALL' for debugging, '0' for use

        //array of known file types (used for icons)
        $file_class = array('swf','txt','zip','gz','rar','cab','tar','7z','mp3','ogg','mid','avi','mpg','flv','mpeg','pdf','tif','exe');
        $directory_restriction = array('../media/library','../media/library/images','../media/library/documents','../media/library/media');
        $titlelist = array('image'=>'Image','advvideo'=>'Video','advaudio'=>'Audio','advfile'=>'Document');
      echo('<table id="file-list">');
      
    $thmb_size = 100;       	//max size of preview thumbnail
    $no_script = false;       //true/false - turns scripts into text files
    $lang = 'en';   
      $d = opendir($c);
      $i = 0;
      while($f = readdir($d)) {
        if(strpos($f, '.') === 0) continue;
        $ff = $c . '/' . $f;
        $ext = strtolower(substr(strrchr($f, '.'), 1));
        if(!is_dir($ff)) {
    	    echo '<tr' . ($i%2 ? ' class="light"' : ' class="dark"') .'>';
    	    //show preview and different icon, if file is image
    	    $imageinfo = @getimagesize($ff);
    	    if($imageinfo && $imageinfo[2] > 0 && $imageinfo[2]< 4) {
    	    	$resize = '';
    	    	if($imageinfo[0] > $thmb_size or $imageinfo[1] > $thmb_size) {
    		    	if($imageinfo[0] > $imageinfo[1]) {
    						$resize = ' style="width: ' . $thmb_size . 'px;"';
    					} else {
    						$resize = ' style="height: ' . $thmb_size . 'px;"';
    					}
    				}
    				if ($imageinfo[2] == 1) {
    					$imagetype = "image_gif";
    				} elseif ($imageinfo[2] == 2) {
    					$imagetype = "image_jpg";
    				} elseif ($imageinfo[2] == 3) {
    					$imagetype = "image_jpg";
    				} else {
    					$imagetype = "image";
    				}
                    
                   
    				echo '<td><a class="file thumbnail ' . $imagetype . '" href="#" onclick="submit_url(\'' . $this->remove_path($this->url_remove_dot_segments($ff),$_GET['type']) . '\');">' . $f . '<span><img' . $resize . ' src="' .$this->remove_path($ff,$_GET['type']). '" /></span></a>'; echo '</td>';
    			//known file types
    			} elseif(in_array($ext,$file_class)) {
    				echo '<td><a class="file file_' . $ext . '" href="#" onclick="submit_url(\'' . $this->url_remove_dot_segments($ff) . '\');">' . $f . '</a>'; echo '</td>';
    			//all other files
    			} else {
    				echo '<td><a class="file unknown" href="#" onclick="submit_url(\'' . $this->url_remove_dot_segments($ff) . '\');">' . $f . '</a>'; echo '</td>';
    	    }
    			echo '<td>' . $this->byte_convert(filesize($ff)) . '</td>';
    			echo '<td class="delete"><a style="cursor:pointer;" title="' . $lng['delete_title'] . '" onclick="delete_file(\'' . $c . '\',\'' . $f . '\');">' . $lng['delete'] . '</a></td>';
    	    echo '</tr>';
    	    $i++;
        }
      }
      echo('</table>');
    }
    
    function delete_directory($dirname) {
    	global $directory_restriction;
    	if (is_dir($dirname))
    		$dir_handle = opendir($dirname);
    	if (!$dir_handle)
    		return false;
    	if(in_array($dirname,$directory_restriction))
    		return false;
    	while($file = readdir($dir_handle)) {
    		if ($file != "." && $file != "..") {
    			if (!is_dir($dirname."/".$file))
    				unlink($dirname."/".$file);
    			else
    				delete_directory($dirname.'/'.$file);
    		}
    	}
    	closedir($dir_handle);
    	rmdir($dirname);
    	return true;
    }
     function remove_path($file,$mode) {
         if($mode == "image")
         {
               $images = explode("images/",$file);
         }
         else if($mode == "advfile")
         {
             $images = explode("documents/",$file);
         }
         if($mode == "image")
         {
             return CMS_UPLOADS_PATH_REL.$images['1'];
         }
         else if($mode == "advfile")
         {
              return CMS_UPLOADS_PATH_REL_DOC.$images['1'];
         }
    }

}

