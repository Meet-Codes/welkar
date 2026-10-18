<?php
$svgContent = file_get_contents('E:/welkar/public/assets/images/marketplaces/amazon_official.svg');

// Add viewBox if not present
if (!str_contains($svgContent, 'viewBox')) {
    $svgContent = str_replace('height="182"', 'height="182" viewBox="0 0 603 182"', $svgContent);
}

// Save standard amazon.svg with dark text
file_put_contents('E:/welkar/public/assets/images/marketplaces/amazon.svg', $svgContent);

// Save amazon_white.svg with white text for dark backgrounds
$whiteSvg = str_replace('style="fill:#221f1f"', 'style="fill:#FFFFFF"', $svgContent);
file_put_contents('E:/welkar/public/assets/images/marketplaces/amazon_white.svg', $whiteSvg);

echo "amazon.svg and amazon_white.svg generated successfully!\n";
