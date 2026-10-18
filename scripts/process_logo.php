<?php

$srcPath = __DIR__ . '/../public/assets/images/welkar-logo-official.png';
$destDir = __DIR__ . '/../public/assets/images';

$im = imagecreatefrompng($srcPath);
if (!$im) {
    die("Failed to open image\n");
}

$w = imagesx($im);
$h = imagesy($im);

$minX = $w;
$minY = $h;
$maxX = 0;
$maxY = 0;

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $c = imagecolorat($im, $x, $y);
        $alpha = ($c >> 24) & 0x7F;
        
        // If not transparent (alpha < 120, where 127 is completely transparent in GD)
        if ($alpha < 120) {
            if ($x < $minX) $minX = $x;
            if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y;
            if ($y > $maxY) $maxY = $y;
        }
    }
}

echo "Actual Logo Bounding Box: X=[$minX, $maxX], Y=[$minY, $maxY]\n";
$actualW = $maxX - $minX + 1;
$actualH = $maxY - $minY + 1;
echo "Dimensions of logo lettering: {$actualW} x {$actualH}\n";

$pad = 16;
$cropX = max(0, $minX - $pad);
$cropY = max(0, $minY - $pad);
$cropW = min($w - $cropX, $actualW + ($pad * 2));
$cropH = min($h - $cropY, $actualH + ($pad * 2));

// 1. Cropped Transparent Logo (Primary Official Logo for navbar, light backgrounds)
$croppedTrans = imagecreatetruecolor($cropW, $cropH);
imagealphablending($croppedTrans, false);
imagesavealpha($croppedTrans, true);
$transColor = imagecolorallocatealpha($croppedTrans, 0, 0, 0, 127);
imagefill($croppedTrans, 0, 0, $transColor);
imagecopy($croppedTrans, $im, 0, 0, $cropX, $cropY, $cropW, $cropH);
imagepng($croppedTrans, $destDir . '/welkar-logo.png');

// 2. White Version for Dark Backgrounds (like Dark Footer, Admin Dark Sidebar)
$whiteLogo = imagecreatetruecolor($cropW, $cropH);
imagealphablending($whiteLogo, false);
imagesavealpha($whiteLogo, true);
imagefill($whiteLogo, 0, 0, $transColor);

for ($y = 0; $y < $cropH; $y++) {
    for ($x = 0; $x < $cropW; $x++) {
        $rgb = imagecolorat($croppedTrans, $x, $y);
        $alpha = ($rgb >> 24) & 0x7F;
        if ($alpha < 127) {
            $c = imagecolorallocatealpha($whiteLogo, 255, 255, 255, $alpha);
            imagesetpixel($whiteLogo, $x, $y, $c);
        }
    }
}
imagepng($whiteLogo, $destDir . '/welkar-logo-white.png');

// 3. Favicon (Clean 64x64 icon)
$fav = imagecreatetruecolor(64, 64);
imagealphablending($fav, false);
imagesavealpha($fav, true);
imagefill($fav, 0, 0, $transColor);
$favTargetH = (int)(64 * ($cropH / $cropW));
$favTargetY = (int)((64 - $favTargetH) / 2);
imagecopyresampled($fav, $croppedTrans, 0, $favTargetY, 0, 0, 64, $favTargetH, $cropW, $cropH);
imagepng($fav, __DIR__ . '/../public/favicon.png');

echo "Successfully created:\n";
echo " - /assets/images/welkar-logo.png ($cropW x $cropH)\n";
echo " - /assets/images/welkar-logo-white.png ($cropW x $cropH)\n";
echo " - /assets/images/welkar-logo-official.png (Full 1023x1024 master)\n";
echo " - /favicon.png (64x64)\n";
