<?php

$default_size = array(300, 200);

$image_width = (isset($_GET['w']) && !empty($_GET['w'])) ? $_GET['w'] : $default_size[0];
$image_height = (isset($_GET['h']) && !empty($_GET['h'])) ? $_GET['h'] : $default_size[1];
$text = (isset($_GET['text']) && !empty($_GET['text'])) ? $_GET['text'] : $image_width . 'x' . $image_height;
$ext = (isset($_GET['ext']) && !empty($_GET['ext'])) ? $_GET['ext'] : 'gif';


$ttf = 'font/typoslab.ttf';

$image = imagecreate($image_width, $image_height);

$color_bg = imagecolorallocate($image, 204, 204, 204);
$color_txt = imagecolorallocate($image, 150, 150, 150);

imagerectangle($image, 0, 0, $image_width - 1, $image_height - 1, $color_bg);

$font_size = 30;
$font_size = min(35, $image_width / 10, $image_height / 3);

$bounds = imagettfbbox($font_size, 0, $ttf, $text);
	
$font_width = abs(max($bounds[2], $bounds[4]));
$font_height = abs(max($bounds[5], $bounds[7]));

// Se supero la larghezza dell'immagine, ricalcolo e strizzo tutto.
$max_width = $image_width - 20;
if ($font_width > $max_width) {
	$font_size /= $font_width / $max_width;
	
	$bounds = imagettfbbox($font_size, 0, $ttf, $text);
	
	$font_width = abs(max($bounds[2], $bounds[4]));
	$font_height = abs(max($bounds[5], $bounds[7]));
} 

$pos_x = intval(($image_width - $font_width) / 2);
$pos_y = intval(($image_height + $font_height) / 2);

imagettftext($image, $font_size, 0, $pos_x, $pos_y, $color_txt, $ttf, $text);

switch($ext) {
	case 'jpg': case 'jpeg':
		header("Content-type: image/jpeg");
		imagejpeg($image);
	break;
	case 'png':
		header("Content-type: image/png");
		imagepng($image);
	break;
	case 'gif': default:
		header("Content-type: image/gif");
		imagegif($image);
	break;
}
?>