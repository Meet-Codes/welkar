<?php
$html = file_get_contents('http://127.0.0.1:8000/');
$logos = ['amazon.png', 'flipkart.png', 'ajio.png', 'myntra.png', 'jiomart.png', 'nykaa.png'];
foreach ($logos as $l) {
    echo $l . ': ' . (strpos($html, $l) !== false ? 'FOUND' : 'MISSING') . PHP_EOL;
}
