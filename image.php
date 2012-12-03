<?php

$default_size = array(300, 200);
$exts = array('gif', 'jpg', 'jpeg', 'png');

$w = isset($_GET['w']) ? $_GET['w'] : null;
$h = isset($_GET['h']) ? $_GET['h'] : null;
$t = isset($_GET['text']) ? $_GET['text'] : null;
$e = isset($_GET['ext']) ? $_GET['ext'] : null; 

$image_width = !empty($w) ? $w : $default_size[0];
$image_height = !empty($h) ? $h : $default_size[1];
$default_text = $image_width . 'x' . $image_height;
$text = !empty($t) ? $t : $default_text;
$ext = !empty($e) ? $e : 'gif';

// se ho scritto image/300/200/png il terzo parametro (text) vale come extensione, non come testo
if (!empty($t) && (in_array($text, $exts) && empty($e))) {
	$ext = $text;
	$text = $default_text;
}


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