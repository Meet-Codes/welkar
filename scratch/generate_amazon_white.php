<?php
// Script to generate amazon_white.png (white text + orange smile on transparent)
$srcPath = 'E:/welkar/public/assets/images/marketplaces/amazon.png';
$srcImg = imagecreatefrompng($srcPath);
$w = imagesx($srcImg);
$h = imagesy($srcImg);

$outImg = imagecreatetruecolor($w, $h);
imagealphablending($outImg, false);
imagesavealpha($outImg, true);
$trans = imagecolorallocatealpha($outImg, 0, 0, 0, 127);
imagefill($outImg, 0, 0, $trans);

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgba = imagecolorat($srcImg, $x, $y);
        $alpha = ($rgba >> 24) & 0x7F;
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        if ($alpha < 127) {
            // If pixel is orange (r > 200, g is around 130-170, b < 50), keep orange
            $isOrange = ($r > 180 && $g > 100 && $g < 185 && $b < 60);
            if ($isOrange) {
                $color = imagecolorallocatealpha($outImg, $r, $g, $b, $alpha);
            } else {
                // Turn dark letter pixels into white, maintaining original alpha anti-aliasing
                $color = imagecolorallocatealpha($outImg, 255, 255, 255, $alpha);
            }
            imagesetpixel($outImg, $x, $y, $color);
        }
    }
}

imagepng($outImg, 'E:/welkar/public/assets/images/marketplaces/amazon_white.png', 9);
imagedestroy($outImg);
imagedestroy($srcImg);
echo "amazon_white.png generated successfully!\n";
