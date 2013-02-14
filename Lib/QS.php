<?php
if (!defined('MAGICK_PATH')) {
	define('MAGICK_PATH_FINAL', 'convert');
} elseif (strpos(strtolower(MAGICK_PATH), 'c:\\') !== false) {
	define('MAGICK_PATH_FINAL', '"' . MAGICK_PATH . '"');
} else {
	define('MAGICK_PATH_FINAL', MAGICK_PATH);
}

if (!defined('FORCE_GD')) {
	define('FORCE_GD', 0);
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
	public function imageResize($options) {
		$defaults = array(
			'name' => null,
			'filename' => null,
			'new_w' => 176,
			'new_h' => 132,
			'quality' => 75,
			'square' => false,
			'gd' => null,
			'sharpening' => 1,
			'x' => 50,
			'y' => 50,
			'force' => false
		);
		$options = array_merge($defaults, $options);

		/**/

		$old_mask = umask(0);

		if (is_null($options['gd'])) {
			$options['gd'] = self::gdVersion();
		}

		settype($options['gd'], 'integer');
		
			$ext = QS::findexts(basename($options['filename']));

			switch(true) {
				case preg_match("/jpg|jpeg|JPG|JPEG/", $ext):
					if (imagetypes() & IMG_JPG) {
						$src_img = imagecreatefromjpeg($options['name']);
						$type = 'jpg';
					} else {
						return;
					}
				break;

				case preg_match("/png/", $ext):
					if (imagetypes() & IMG_PNG) {
						$src_img = imagecreatefrompng($options['name']);
						$type = 'png';
					} else {
						return;
					}
				break;

				case preg_match("/gif|GIF/", $ext):
					if (imagetypes() & IMG_GIF) {
						$src_img = imagecreatefromgif($options['name']);
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

			if ($options['new_w'] == $old_x && $options['new_h'] == $old_y && !$options['force']) {
				imagedestroy($src_img);
				copy($name, $filename);
				return;
			}			
			
			$original_aspect = $old_x / $old_y;
			$new_aspect = $options['new_w'] / $options['new_h'];

			if ($options['square']) {
				if (($options['new_w'] > $old_x || $options['new_h'] > $old_y) && !$options['force']) {
					copy($options['name'], $options['filename']);
					return;
				}

				if ($original_aspect >= $new_aspect) {
					$thumb_w = ($options['new_h'] * $old_x) / $old_y;
					$thumb_h = $options['new_h'];
					$pos_x = $thumb_w * ($options['x'] / 100);
					$pos_y = $thumb_h * ($options['y'] / 100);
				} else {
					$thumb_w = $options['new_w'];
					$thumb_h = ($options['new_w'] * $old_y) / $old_x;
					$pos_x = $thumb_w * ($options['x'] / 100);
					$pos_y = $thumb_h * ($options['y'] / 100);
				}

				$crop_y = $pos_y - ($options['new_h'] / 2);
				$crop_x = $pos_x - ($options['new_w'] / 2);

				if ($crop_y < 0) {
					$crop_y = 0;
				} else if (($crop_y + $options['new_h']) > $thumb_h) {
					$crop_y = $thumb_h - $options['new_h'];
				}

				if ($crop_x < 0) {
					$crop_x = 0;
				} else if (($crop_x + $options['new_w']) > $thumb_w) {
					$crop_x = $thumb_w - $options['new_w'];
				}
			} else {
				$crop_y = 0;
				$crop_x = 0;

				if ($original_aspect >= $new_aspect) {
					if ($options['new_w'] > $old_x && !$options['force']) {
						imagedestroy($src_img);
						copy($options['name'], $options['filename']);

						return;
					}

					$thumb_w = $options['new_w'];
					$thumb_h = ($options['new_w'] * $old_y) / $old_x;
				} else {
					if ($options['new_h'] > $old_y && !$options['force']) {
						imagedestroy($src_img);
						copy($options['name'], $options['filename']);
						
						return;
					}

					$thumb_w = ($options['new_h'] * $old_x) / $old_y;
					$thumb_h = $options['new_h'];
				}			
			}

			if ($options['gd'] != 2) {
				$dst_img_one = imagecreate($thumb_w, $thumb_h);
				imagecopyresized($dst_img_one, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);    
			} else {
				if ($type == 'png') {
					$dst_img_one = imagecreatetruecolor($thumb_w, $thumb_h);
				    $trans_colour = imagecolorallocatealpha($dst_img_one, 0, 0, 0, 127);
				    imagefill($dst_img_one, 0, 0, $trans_colour);
				} else {
					$dst_img_one = imagecreatetruecolor($thumb_w, $thumb_h);
				}
				imagecopyresampled($dst_img_one, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y); 
			}

			if ($options['square']) {
				if ($options['gd'] != 2) {
					$dst_img = imagecreate($options['new_w'], $options['new_h']);
					imagecopyresized($dst_img, $dst_img_one, 0, 0, $crop_x, $crop_y, $options['new_w'], $options['new_h'], $options['new_w'], $options['new_h']);    
				} else {
					if ($type == 'png') {
						$dst_img = imagecreatetruecolor($options['new_w'], $options['new_h']);
					    $trans_colour = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
					    imagefill($dst_img, 0, 0, $trans_colour);
					} else {
						$dst_img = imagecreatetruecolor($options['new_w'], $options['new_h']);
					}
					imagecopyresampled($dst_img, $dst_img_one, 0, 0, $crop_x, $crop_y, $options['new_w'], $options['new_h'], $options['new_w'], $options['new_h']); 
				}
			} else {
				$dst_img = $dst_img_one;
			}

			if ($type == 'png') {
				imagealphablending($dst_img, false);
				imagesavealpha($dst_img, true);
				imagepng($dst_img, $options['filename']); 
			} elseif ($type == 'gif') {
				imagegif($dst_img, $options['filename']);
			} else {
				imagejpeg($dst_img, $options['filename'], $options['quality']); 
			}

			imagedestroy($dst_img);
			imagedestroy($dst_img_one); 
			imagedestroy($src_img); 
			umask($old_mask);
	}

	public function rotateImage($sourceFile, $destImageName, $degreeOfRotation) {
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

	public function gdVersion() {
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

	public function _gd() {
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
	public function findexts($filename) {
		$filename = strtolower($filename) ;
		$exts = explode("[/\\.]", $filename) ;
		$n = count($exts)-1;
		$exts = $exts[$n];

		return $exts;
	}

/**
 * Return URL for the given video file
 *
 * @param string $movie_path full path to video file
 */
	public function movieThumbUrl($options) {
		$defaults = array(
			'src' => '',
			'album_id' => null,
			'width' => 176,
			'height' => 132,
			'square' => 1,
			'quality' => 70,
			'sharpening' => 1,
			'anchor_x' => 50,
			'anchor_y' => 50,
			'force' => false
		);
		$options = array_merge($defaults, $options);
		$base_dir = QS_FOLDER . "album-{$options['album_id']}";
		$pos = strrpos($options['src'], '.');
		$clean = substr($options['src'], 0, $pos);
		$custom = glob($base_dir . DS . '___tn___' . $clean . '.*');

		if (count($custom)) {
			$filename = basename($custom[0]);
			$options['src'] = $filename;
			$encode = QS::p($options);

			return Router::url('/quick_slide/images/p/' . $encode, true);
		}

		$icons = '/quick_slide/img/icons/';
		$ext = strtolower(QS::findexts($options['src']));

		if (in_array($ext, array('swf', 'mov', 'mp4', 'flv'))) {
			$filename = "{$icons}default_{$ext}.gif";
		} else {
			$filename = "{$icons}default_swf.gif";
		}

		return Router::url($filename, true);
	}

	public function isVideo($fn) {
		return preg_match('/(\.flv|\.mov|\.mp4|\.m4a|\.m4v|\.3gp|\.3g2)/', $fn);
	}

	public function isSwf($fn) {
		return preg_match('/\.swf/', $fn);
	}

	public function isImg($fn) {
		return (!QS::isSwf($fn) && !QS::isVideo($fn));
	}

	public function getFilename($fullFileName) {
		$fullFileName = basename($fullFileName);
		$pos = strrpos($fullFileName, '.');
		$clean = substr($fullFileName, 0, $pos);

		return $clean;
	}	
/**
 * Grab all files in a directory.
 *
 */
	public function directory($dir, $filters = 'all') {
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
 * Picture encoder
 *
 * 0: 'src'
 * 1: 'album_id'
 * 2: 'width'
 * 3: 'height'
 * 4: 'square'
 * 5: 'quality'
 * 6: 'sharpening'
 * 7: 'anchor_x'
 * 8: 'anchor_y'
 * 9: 'force'
 *			
 */
	public function p($options) {
		$defaults = array(
			'src' => '',
			'album_id' => null,
			'width' => 176,
			'height' => 132,
			'square' => 1,
			'quality' => 70,
			'sharpening' => 1,
			'anchor_x' => 50,
			'anchor_y' => 50,
			'force' => false
		);

		$options = array_merge($defaults, $options);
		$args = join(',', $options);
		$enc = base64_encode($args);

		if (!$options['album_id']) {
			return false;
		}

		return $enc . '&amp;nc=' . rand(100, 9999);
	}

/*
 * Numeric sanitizer
 *
 */
	public function n($var, $default = false) {
		$var = trim($var);

		if (is_numeric($var)) {
			return $var;
		} else if (is_numeric($default)) {
			return $default;
		} else {
			exit;
		}
	}

/**
 * Recursive make dir.
 *
 * @param string $pathname Folder path.
 * @param mixed $mode
 */
	public function rmkdir($pathname, $mode = 0777) {
		if (is_array($pathname)) {
			foreach ($pathname as $path) {
				QS::rmkdir($path, $mode);
			}
		} else {
			is_dir(dirname($pathname)) || QS::rmkdir(dirname($pathname), $mode);

			return is_dir($pathname) || @mkdir($pathname, $mode);
		}
	}
}