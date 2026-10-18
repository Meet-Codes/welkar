<?php

$url = 'http://localhost:8080/product/welkar-royale-classic-roman-gold';
$html = file_get_contents($url);

if (!$html) {
    echo "ERROR: Failed to fetch {$url}\n";
    exit(1);
}

echo "Fetched HTML successfully (" . strlen($html) . " bytes)\n\n";

$checks = [
    'Module 1: Breadcrumbs' => 'breadcrumb',
    'Module 2: Horology Hero / Viewport' => 'pdp-viewport-container',
    'Module 2: Live Dial SVGs/Canvas' => 'welkar-dial-overlay',
    'Module 3: 4-Pillar Horology Feature Strip' => '316L Surgical Steel',
    'Module 3: Pillar 2 Japanese Movement' => 'Japanese Movement',
    'Module 3: Pillar 3 Hydrostatic Seal' => '30M Hydrostatic Seal',
    'Module 3: Pillar 4 Mineral Glass' => 'Hardened Mineral Glass',
    'Module 4: Editorial Wide A+ Banner' => 'CALIBRATED FOR HOROLOGICAL DISTINCTION',
    'Module 5: Interactive Horological Anatomy Hotspots' => 'hotspot-pin',
    'Module 5: Hotspot Detail Box' => 'hotspot-detail-box',
    'Module 6: "On The Wrist" Lifestyle Section' => 'On The Wrist',
    'Module 7: Alternating Craftsmanship Feature Stories' => 'story-block',
    'Module 8: Technical Horological Specifications' => 'Technical Specifications',
    'Module 10: Family Comparison Matrix' => 'comparison-table',
    'Module 11: Horology Pre-Purchase FAQs' => 'Frequently Asked Questions',
    'Module 12: Customer Sentiment Score' => 'Customer Sentiment',
    'Module 12: Rating Distribution Progress Bars' => 'review-bar-fill',
    'Module 13: Verified Customer Review Cards' => 'Verified Purchase',
    'Module 14: Related Timepieces ("Complete The Atelier")' => 'You May Also Appreciate',
    'Module 15: Sticky Purchase Reminder Bar' => 'sticky-purchase-bar',
    'Review Submission Modal' => 'writeReviewModal',
    'Review Rating Select' => 'Exceptional Quality (5 Stars)',
    'Design System: Midnight Sapphire Theme' => '#0A1128'
];

$passed = 0;
foreach ($checks as $label => $needle) {
    $found = strpos($html, $needle) !== false;
    echo ($found ? "[PASS]" : "[FAIL]") . " {$label} ('{$needle}')\n";
    if ($found) $passed++;
}

echo "\nSummary: {$passed} / " . count($checks) . " checks passed.\n";

// Test Helpful Vote API endpoint
$voteContext = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content' => '',
        'ignore_errors' => true
    ]
]);
$voteRes = file_get_contents('http://localhost:8080/api/reviews/5/vote', false, $voteContext);
echo "\nHelpful Vote API test:\n" . substr($voteRes, 0, 200) . "\n";

// Test Review Submission
$reviewPostData = http_build_query([
    'author_name' => 'Kavita Chawla',
    'rating' => 5,
    'title' => 'Exquisite finish and weight',
    'comment' => 'The gold bezel and polished indices reflect light gorgeously. The butterfly clasp feels solid and secure on the wrist.',
    'order_number' => 'WEL-2026-1035'
]);
$reviewContext = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\nX-Requested-With: XMLHttpRequest\r\n",
        'content' => $reviewPostData,
        'ignore_errors' => true
    ]
]);
$reviewRes = file_get_contents('http://localhost:8080/product/welkar-royale-classic-roman-gold/review', false, $reviewContext);
echo "\nReview Submission test:\n" . substr($reviewRes, 0, 200) . "\n";
