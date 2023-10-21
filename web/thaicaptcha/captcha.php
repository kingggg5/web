<?php

session_start();
$rand = substr(str_shuffle('123456789'), 0, 6);
$_SESSION['thaicaptcha_md5'] = md5($rand);
$font = array('THSarabunNewBold.ttf');
$randomfont = array_rand($font);
$fontshow = $font[$randomfont];
header('Content-type: image/png');
$im = imagecreatetruecolor(157, 38);
$bg_color = imagecolorallocate($im, 61, 72, 76);
$font_color = imagecolorallocate($im, 255, 255, 255);
imagefilledrectangle($im, 0, 0, 399, 80, $bg_color);
imagettftext($im, 40, 0, 5, 30, $font_color, $fontshow, $rand);
imagepng($im);
imagedestroy($im);
?>