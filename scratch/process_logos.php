<?php
// Script to process user-uploaded marketplace logos

$sources = [
    'ajio' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314230207.png',
    'myntra' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314268630.png',
    'jiomart' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314291943.png',
    'nykaa' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314341699.png',
    'flipkart' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314377627.png'
];

$destDir = 'E:/welkar/public/assets/images/marketplaces';
if (!is_dir($destDir)) {
    mkdir($destDir, 0777, true);
}

foreach ($sources as $name => $srcPath) {
    $srcImg = imagecreatefrompng($srcPath);
    $w = imagesx($srcImg);
    $h = imagesy($srcImg);
    
    // Find bounding box of content (non-white, non-transparent)
    $minX = $w; $minY = $h; $maxX = 0; $maxY = 0;
    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgba = imagecolorat($srcImg, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            $r = ($rgba >> 16) & 0xFF;
            $g = ($rgba >> 8) & 0xFF;
            $b = $rgba & 0xFF;
            
            $isTransparent = ($alpha > 90);
            $isWhite = ($r >= 250 && $g >= 250 && $b >= 250);
            
            if (!$isTransparent && !$isWhite) {
                if ($x < $minX) $minX = $x;
                if ($x > $maxX) $maxX = $x;
                if ($y < $minY) $minY = $y;
                if ($y > $maxY) $maxY = $y;
            }
        }
    }
    
    // Add small padding around bounding box (4% of height)
    $padY = max(6, (int)(($maxY - $minY) * 0.04));
    $padX = max(8, (int)(($maxX - $minX) * 0.04));
    
    $cropX = max(0, $minX - $padX);
    $cropY = max(0, $minY - $padY);
    $cropW = min($w - $cropX, ($maxX - $minX + 1) + 2 * $padX);
    $cropH = min($h - $cropY, ($maxY - $minY + 1) + 2 * $padY);
    
    // Create cropped image
    $cropImg = imagecreatetruecolor($cropW, $cropH);
    imagealphablending($cropImg, false);
    imagesavealpha($cropImg, true);
    
    // Fill transparent by default
    $trans = imagecolorallocatealpha($cropImg, 0, 0, 0, 127);
    imagefill($cropImg, 0, 0, $trans);
    
    imagecopy($cropImg, $srcImg, 0, 0, $cropX, $cropY, $cropW, $cropH);
    
    // Also create transparent version where white pixels become transparent
    $transImg = imagecreatetruecolor($cropW, $cropH);
    imagealphablending($transImg, false);
    imagesavealpha($transImg, true);
    imagefill($transImg, 0, 0, $trans);
    
    for ($y = 0; $y < $cropH; $y++) {
        for ($x = 0; $x < $cropW; $x++) {
            $rgba = imagecolorat($cropImg, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            $r = ($rgba >> 16) & 0xFF;
            $g = ($rgba >> 8) & 0xFF;
            $b = $rgba & 0xFF;
            
            if ($alpha < 100) {
                // If pixel is very close to white, make transparent with gradient falloff
                $brightness = ($r + $g + $b) / 3.0;
                if ($brightness > 248) {
                    $newAlpha = 127;
                } elseif ($brightness > 230) {
                    $ratio = ($brightness - 230) / (248 - 230);
                    $newAlpha = (int)($ratio * 127);
                } else {
                    $newAlpha = $alpha;
                }
                $newColor = imagecolorallocatealpha($transImg, $r, $g, $b, $newAlpha);
                imagesetpixel($transImg, $x, $y, $newColor);
            }
        }
    }
    
    // Save cropped version (preserving original background)
    imagepng($cropImg, "$destDir/{$name}_cropped.png", 9);
    // Save transparent version
    imagepng($transImg, "$destDir/{$name}_transparent.png", 9);
    // Default logo
    imagepng($cropImg, "$destDir/{$name}.png", 9);
    
    echo "Saved $name: cropped size {$cropW}x{$cropH}\n";
    
    imagedestroy($cropImg);
    imagedestroy($transImg);
    imagedestroy($srcImg);
}

echo "All uploaded logos processed successfully!\n";
