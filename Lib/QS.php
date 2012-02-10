<?php
if (!defined('MAGICK_PATH')) {
    define('MAGICK_PATH_FINAL', 'convert');
} elseif (strpos(strtolower(MAGICK_PATH), 'c:\\') !== false) {
    define('MAGICK_PATH_FINAL', '"' . MAGICK_PATH . '"');	
} else {
    define('MAGICK_PATH_FINAL', MAGICK_PATH);
}

if (!function_exists('imagerotate')) {
    function imagerotate($src_img, $angle) {
        $src_x = imagesx($src_img);
        $src_y = imagesy($src_img);

        if ($angle == 180) {
            $dest_x = $src_x;
            $dest_y = $src_y;
        } elseif ($src_x <= $src_y) {
            $dest_x = $src_y;
            $dest_y = $src_x;
        } elseif ($src_x >= $src_y) {
            $dest_x = $src_y;
            $dest_y = $src_x;
        }

        $rotate = imagecreatetruecolor($dest_x,$dest_y);

        imagealphablending($rotate, false);

        switch ($angle) {
            case -90:
                case 270:
                    for ($y = 0; $y < ($src_y); $y++) {
                        for ($x = 0; $x < ($src_x); $x++) {
                            $color = imagecolorat($src_img, $x, $y);
                            
                            imagesetpixel($rotate, $dest_x - $y - 1, $x, $color);
                        }
                    }
            break;

            case 90: // left
                for ($y = 0; $y < ($src_y); $y++) {
                    for ($x = 0; $x < ($src_x); $x++) {
                        $color = imagecolorat($src_img, $x, $y);
                        
                        imagesetpixel($rotate, $y, $dest_y - $x - 1, $color);
                    }
                }
            break;

            case 180:
                for ($y = 0; $y < ($src_y); $y++) {
                    for ($x = 0; $x < ($src_x); $x++) {
                        $color = imagecolorat($src_img, $x, $y);
                        
                        imagesetpixel($rotate, $dest_x - $x - 1, $dest_y - $y - 1, $color);
                    }
                }
            break;

            default:
                $rotate = $src_img;
        }

        return $rotate;
    }
}

class QS {
    function image_resize($filename, $new_w, $new_h, $quality, $square = false, $x, $y, $force = false) {
        $ext = QS::findexts(basename($filename));
        $ThumbFolder = str_replace(basename($filename), '', $filename) . 'cache' . DS;

        if (!file_exists($ThumbFolder)) { 
            QS::rmkdir($ThumbFolder);
        }

        $ThumbCacheName	= str_replace(".{$ext}", "", basename($filename)) . "_{$new_w}_{$new_h}_{$quality}_{$square}_{$x}_{$y}_{$force}.{$ext}";
        $ThumOld = file_exists($ThumbFolder.$ThumbCacheName) ? filectime($ThumbFolder.$ThumbCacheName) : 0;
        $name = $filename;
        $filename = $ThumbFolder.$ThumbCacheName;

        if ((time()-604800) > $ThumOld) {
            switch(true) {
                case preg_match("/jpg|jpeg|JPG|JPEG/", $ext):
                    if (imagetypes() & IMG_JPG) {
                        $src_img = imagecreatefromjpeg($name);
                        $type = 'jpg';
                    } else {
                        return;
                    }
                break;

                case preg_match("/png/", $ext):
                    if (imagetypes() & IMG_PNG) {
                        $src_img = imagecreatefrompng($name);
                        $type = 'png';
                    } else {
                        return;
                    }
                break;

                case preg_match("/gif|GIF/", $ext):
                    if (imagetypes() & IMG_GIF) { 
                        $src_img = imagecreatefromgif($name);
                        $type = 'gif';
                    } else {
                        return;
                    }
                break;
            }

            if (!isset($src_img)) { 
                return;
            }

            $old_x = imagesx($src_img);
            $old_y = imagesy($src_img);
            $original_aspect = $old_x/$old_y;
            $new_aspect = $new_w/$new_h;

            if ($square) {
                if ($original_aspect >= $new_aspect) {
                    $thumb_w = ($new_h*$old_x)/$old_y;
                    $thumb_h = $new_h;				
                    $pos_x = $thumb_w * ($x/100);
                    $pos_y = $thumb_h * ($y/100);
                } else {
                    $thumb_w = $new_w;
                    $thumb_h = ($new_w*$old_y)/$old_x;
                    $pos_x = $thumb_w * ($x/100);
                    $pos_y = $thumb_h * ($y/100);
                }

                $crop_y = $pos_y - ($new_h/2);
                $crop_x = $pos_x - ($new_w/2);

                if ($crop_y < 0) { 
                    $crop_y = 0;
                } else if (($crop_y+$new_h) > $thumb_h) {
                    $crop_y = $thumb_h - $new_h;
                }

                if ($crop_x < 0) { 
                    $crop_x = 0;
                } else if (($crop_x+$new_w) > $thumb_w) {
                    $crop_x = $thumb_w - $new_w;
                }
            } else {
                $crop_y = 0;
                $crop_x = 0;

                if ($original_aspect >= $new_aspect) {
                    if ($new_w > $old_x) {
                        copy($name, $filename);
                    }

                    $thumb_w = $new_w;
                    $thumb_h = ($new_w*$old_y)/$old_x;
                } else {
                    if ($new_h > $old_y) {
                        copy($name, $filename); 
                    }

                    $thumb_w = ($new_h*$old_x)/$old_y;
                    $thumb_h = $new_h;
                }
            }

            $dst_img_one = imagecreatetruecolor($thumb_w,$thumb_h);

            imagecopyresampled($dst_img_one, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y); 

            if ($square) {
                $dst_img = imagecreatetruecolor($new_w, $new_h);

                imagecopyresampled($dst_img, $dst_img_one, 0, 0, $crop_x, $crop_y, $new_w, $new_h, $new_w, $new_h); 
            } else {
                $dst_img = $dst_img_one;
            }

            if ($type == 'png') {
                imagepng($dst_img, $filename); 
            } elseif ($type == 'gif') {
                imagegif($dst_img, $filename);
            } else {
                imagejpeg($dst_img, $filename, $quality); 
            }

            imagedestroy($dst_img);
            imagedestroy($dst_img_one); 
            imagedestroy($src_img); 

            $specs = getimagesize($filename);

            header('Content-type: ' . $specs['mime']);
            header('Content-length: ' . filesize($filename));
            header('Cache-Control: public');
            header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('+1 year')));
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)));

            die(file_get_contents($filename));
        } else {
            $specs = getimagesize($filename);

            header('Content-type: ' . $specs['mime']);
            header('Content-length: ' . filesize($filename));
            header('Cache-Control: public');
            header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('+1 year')));
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)));

            die(file_get_contents($filename));
        }
    }

    function rotateImage($sourceFile, $destImageName, $degreeOfRotation) {
        if (!function_exists('imagerotate') || !file_exists($sourceFile)) {
            return false;
        }	

        $gd = QS::gdVersion();

        if ($gd >= 3) {
            $r = -$degreeOfRotation;
            $cmd = MAGICK_PATH_FINAL . " \"$sourceFile\" -rotate $r \"$destImageName\"";

            exec($cmd);
        } else {
            $imageinfo = getimagesize($sourceFile);

            switch($imageinfo['mime']) {
                case "image/jpg":
                    case "image/jpeg":
                        case "image/pjpeg": //for IE
                            $type = "jpg";
                            $src_img=imagecreatefromjpeg("$sourceFile");
                break;

                case "image/gif":
                    $type = "gif";
                    $src_img = imagecreatefromgif("$sourceFile");
                break;

                case "image/png":
                    case "image/x-png": //for IE
                        $type = "png";
                        $src_img = imagecreatefrompng("$sourceFile");
                break;
            }

            if (!isset($src_img)) { 
                return;
            }

            //rotate the image according to the spcified degree
            if ($type == 'png') {
                $new = imagerotate($src_img, $degreeOfRotation, 0);

                imagepng($new, $destImageName, 9); 
            } elseif ($type == 'gif') {
                if(!function_exists("imagerotate")) {
                    $new = imagerotate($src_img, $degreeOfRotation, true);
                } else {
                    $new = imagerotate($src_img, $degreeOfRotation, 0);
                }

                imagegif($new, $destImageName,100);
            } else {
                $new = imagerotate($src_img, $degreeOfRotation, 0);

                imagejpeg($new, $destImageName, 100); 
            }
        }
    }

	function gdVersion() {
		if (function_exists('exec') &&
            (DS == '/' || (DS == '\\' && MAGICK_PATH_FINAL != 'convert'))
        ) {
			exec(MAGICK_PATH_FINAL . ' -version', $out);

			@$test = $out[0];

			if (!empty($test) && strpos($test, ' not ') === false) {
				$bits = explode(' ', $test);
				$version = $bits[2];

				if (version_compare($version, '6.0.0', '>')) {
					return 4;
				} else {
					return 3;
				}
			} else {
				return QS::_gd();
			}
		} else {
			return QS::_gd();
		}
	}

	function _gd() {
		if (function_exists('gd_info')) {
			$gd = gd_info();
			$version = preg_replace('/[[:alpha:][:space:]()]+/', '', $gd['GD Version']);

			settype($version, 'integer');

			return $version;
	 	} else {
			return 0;
		}
	}

/**
 * Get file extension.
 *
 * @param string $filename Name of the file.
 * @return string
 */
	function findexts ($filename) {
		$filename = strtolower($filename) ;
		$exts = explode("[/\\.]", $filename) ;
		$n = count($exts)-1;
		$exts = $exts[$n];

		return $exts;
	}

    function movieThumb($movie_path){
        $src = basename($movie_path);
        $pos = strrpos($src, '.');
        $clean = substr($src, 0, $pos);
        $custom = glob(str_replace($src, '', $movie_path) . '___tn___' . $clean . '.*');

        if (count($custom)) {
            $filename = str_replace($src, '', $movie_path) . basename($custom[0]);
        } else {
            switch (strtolower(QS::findexts($src))) {
                default:
                    case 'swf"':
                        $filename = str_replace($src, "", $movie_path)."../icons/default_swf.gif";
                break;

                case 'mov':
                    $filename = str_replace($src, "", $movie_path)."../icons/default_mov.gif";
                break;

                case 'mp4':
                    $filename = str_replace($src, "", $movie_path)."../icons/default_mp4.gif";
                break;

                case 'flv':
                    $filename = str_replace($src, "", $movie_path)."../icons/default_flv.gif";
                break;
            }
        }

        return $filename;
    }

    function isVideo($fn) {
        return preg_match('/(\.flv|\.mov|\.mp4|\.m4a|\.m4v|\.3gp|\.3g2)/', $fn);
    }

    function isSwf($fn) {
        return preg_match('/\.swf/', $fn);
    }

    function isImg($fn) {
        return (!QS::isSwf($fn) && !QS::isVideo($fn));
    }

/**
 * Grab all files in a directory.
 *
 */
	function directory($dir, $filters = 'all') {
		if ($filters == 'accepted') { 
            $filters = 'jpg,JPG,JPEG,jpeg,gif,GIF,png,PNG,swf,SWF,flv,FLV';
        }

		$handle = opendir($dir);
		$files = array();

		if ($filters == 'all') {
			while (($file = readdir($handle))!==false) {
				$files[] = $file;
			}
		}

		if ($filters != 'all') {
			$filters = explode(",", $filters);

			while (($file = readdir($handle)) !== false) {
				for ($f=0; $f< sizeof($filters); $f++) {
					$system = explode(".", $file);
					$count = count($system);

					if ($system[$count-1] == $filters[$f]) {
						$files[] = $file;
					}
				}
			}
		}

		closedir($handle);

        return $files;
	}

/**
 * Pic encoder
 *
 */
    function p() {
        $args = func_get_args();
        $args = join(',', $args);
        $enc = base64_encode($args);

        return $enc;
    }

/**
 * Recursive make dir.
 *
 * @param string $pathname Folder path.
 * @param mixed $mode
 */
	function rmkdir($pathname, $mode = 0777){
		if (is_array($pathname)){
			foreach ($pathname as $path){
				QS::rmkdir($path, $mode);
			}
		} else {
			is_dir(dirname($pathname)) || QS::rmkdir(dirname($pathname), $mode);

			return is_dir($pathname) || @mkdir($pathname, $mode);
		}
	}
}