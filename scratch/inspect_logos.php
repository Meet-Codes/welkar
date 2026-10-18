<?php
$files = [
    'ajio' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314230207.png',
    'myntra' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314268630.png',
    'jiomart' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314291943.png',
    'nykaa' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314341699.png',
    'flipkart' => 'C:/Users/mahek/.gemini/antigravity-ide/brain/f41cd4fc-e91f-42d3-a6b2-f7ea22c23795/.user_uploaded/media_1792314377627.png'
];

foreach ($files as $name => $path) {
    $img = imagecreatefrompng($path);
    $w = imagesx($img);
    $h = imagesy($img);
    
    // Find non-white / non-transparent bounding box
    $minX = $w; $minY = $h; $maxX = 0; $maxY = 0;
    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgba = imagecolorat($img, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            $r = ($rgba >> 16) & 0xFF;
            $g = ($rgba >> 8) & 0xFF;
            $b = $rgba & 0xFF;
            
            // Check if not transparent and not white (threshold > 245)
            $isTransparent = ($alpha > 100);
            $isWhite = ($r > 245 && $g > 245 && $b > 245);
            
            if (!$isTransparent && !$isWhite) {
                if ($x < $minX) $minX = $x;
                if ($x > $maxX) $maxX = $x;
                if ($y < $minY) $minY = $y;
                if ($y > $maxY) $maxY = $y;
            }
        }
    }
    $contentW = ($maxX >= $minX) ? ($maxX - $minX + 1) : 0;
    $contentH = ($maxY >= $minY) ? ($maxY - $minY + 1) : 0;
    echo "$name: size={$w}x{$h}, bbox=[$minX, $minY, $maxX, $maxY], content={$contentW}x{$contentH}\n";
}
