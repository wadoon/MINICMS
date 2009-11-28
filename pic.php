<?php


define("FIRSTLEVEL", 0.1);
define("INNERLEVEL", 0.1);

$h = $_GET['height'];
$w = $_GET['width'];

$img = imagecreatetruecolor(200, 200);

$fW = (FIRSTLEVEL * w);
$fH = (FIRSTLEVEL * h);
$iW = (INNERLEVEL * w);
$iH = (INNERLEVEL * h);

$white = imagecolorallocate($img, 255, 255, 255);
$red   = imagecolorallocate($img, 255,   0,   0);
$green = imagecolorallocate($img,   0, 255,   0);
$blue  = imagecolorallocate($img,   0,   0, 255);

imagefilledarc( $img , $w/2-$fW/2, $h/2-$fH/2 int $cx , int $cy , int $width , int $height , int $start , int $end , int $color , int $style )

fillArc(w / 2 - fW / 2, h / 2 - fH / 2, fW, fH, 0, 100);
illArc(w / 2 - fW / 2, h / 2 - fH / 2, fW, fH, 110, 110);
g.fillArc(w / 2 - fW / 2, h / 2 - fH / 2, fW, fH, 230, 100);
g.fillOval(w / 2 - (iW+25) / 2, h / 2 - (iH +25)/ 2, iW+25, iH+25);
g.fillOval(w / 2 - iW / 2, h / 2 - iH / 2, iW, iH);


header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
