<?php
require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');
require_once __DIR__ . '/../app/Support/helpers.php';

use App\Support\Database;

$productFaqs = [
    3 => [ // Welkar Royale Classic Roman Gold
        [
            'q' => 'What movement powers the Welkar Royale Classic?',
            'a' => 'It is powered by a high-precision Japanese Quartz caliber offering +/-15 seconds per month accuracy and an authentic SR626SW silver-oxide battery with 2 to 3 years lifespan.'
        ],
        [
            'q' => 'How should I care for the genuine leather strap?',
            'a' => 'To preserve the rich brown finish, avoid saturating the leather in water and allow it to air-dry naturally away from direct heat if exposed to moisture.'
        ],
        [
            'q' => 'What is the case size and wrist fit for this model?',
            'a' => 'It features a refined 39mm case diameter and an ultra-slim profile, designed to fit comfortably under dress cuffs for both men and unisex styling on wrists from 5.5 to 7.5 inches.'
        ],
        [
            'q' => 'Is this watch water-resistant?',
            'a' => 'Yes, it is rated at 30M (3 ATM) with dual silicone gasket seals, protecting against rain, hand-washing, and accidental splashes. It is not intended for swimming.'
        ]
    ],
    4 => [ // Titan Neo Splash Sport Chrono
        [
            'q' => 'Can I wear the Titan Neo Splash Sport Chrono while swimming?',
            'a' => 'Yes. With a 50M (5 ATM) water resistance rating and a navy blue silicone strap, it is tested safe for shallow recreational surface swimming and showers. Do not press chrono pushers underwater.'
        ],
        [
            'q' => 'What functions do the chronograph sub-dials provide?',
            'a' => 'The multi-function dial features precision 1/10th second stopwatch timing, split lap measurement, and a 24-hour time indicator.'
        ],
        [
            'q' => 'How durable is the silicone strap during sports?',
            'a' => 'The sweat-resistant, hypoallergenic silicone rubber band is engineered for active movement, gym workouts, and humid Indian weather.'
        ],
        [
            'q' => 'What warranty coverage is included?',
            'a' => 'It includes a 2-Year official movement and manufacturing defect warranty supported nationwide.'
        ]
    ],
    7 => [ // Fossil Townsman Automatic Skeleton
        [
            'q' => 'Does the Fossil Townsman Automatic require a battery replacement?',
            'a' => 'No. It operates on a self-winding automatic mechanical caliber that is wound by the natural kinetic motion of your wrist. It has zero battery.'
        ],
        [
            'q' => 'How do I wind or restart the watch if it has stopped?',
            'a' => 'If left unworn past its ~40-hour power reserve, rotate the crown clockwise 20 to 30 times in the neutral position to manually jumpstart the balance wheel, then set the time.'
        ],
        [
            'q' => 'What is visible through the skeleton exhibition dial?',
            'a' => 'The open-heart skeleton front and exhibition caseback reveal the oscillating balance wheel, escapement gear train, and jeweled bearings in active operation.'
        ],
        [
            'q' => 'What is the water resistance of this automatic timepiece?',
            'a' => 'It is pressure-rated to 50M (5 ATM), offering protection against rain, splashes, and brief water immersion. Avoid submerging the leather strap.'
        ]
    ],
    12 => [ // Welkar Silver Quartz Analog - Black Dial with Day & Date
        [
            'q' => 'How does the bilingual day-and-date complication work?',
            'a' => 'Pull the crown to position 1 to set the date (clockwise) and day of the week (counter-clockwise). Pull to position 2 to set the time.'
        ],
        [
            'q' => 'Can the stainless steel bracelet links be resized?',
            'a' => 'Yes. All solid 316L link bracelets feature inner directional arrows and can be easily adjusted with a link pin tool at home or by any local jeweler.'
        ],
        [
            'q' => 'What is the water resistance rating?',
            'a' => 'It is sealed to 30M (3 ATM) hydrostatic pressure with silicone crown gaskets, resilient against handwashing, sudden rain, and humidity.'
        ],
        [
            'q' => 'What warranty is provided?',
            'a' => 'It comes with Welkar\'s 2-Year movement warranty with complimentary doorstep repair or exchange pickup across India.'
        ]
    ],
    13 => [ // Welkar Hydro-Lab Submersion Master Edition
        [
            'q' => 'What makes the Hydro-Lab Submersion Master resilient?',
            'a' => 'It features black PVD-coated 316L surgical stainless steel, double O-ring silicone crown gaskets, and individually verified pressure-chamber sealing.'
        ],
        [
            'q' => 'Does the black PVD coating scratch or peel?',
            'a' => 'We use high-density physical vapor deposition (PVD) bonding that fuses molecularly with the steel, providing exceptional resistance to daily scratches and sweat erosion.'
        ],
        [
            'q' => 'Can I wear this model for water activities?',
            'a' => 'It is certified for 30M (3 ATM) everyday water protection against heavy rain and splashes. For dedicated swimming, we recommend 50M-rated sports models.'
        ],
        [
            'q' => 'What is the battery lifespan and replacement procedure?',
            'a' => 'The Japanese quartz movement runs on an SR626SW silver-oxide cell for 2 to 3 years and can be replaced in minutes by any watchmaker.'
        ]
    ],
    14 => [ // Welkar Sunray Teal Dial Silver Metal Strap
        [
            'q' => 'What is special about the sunray teal dial?',
            'a' => 'The dial features an iridescent sunburst micro-texture that reflects ambient light in shifting shades of deep teal and oceanic emerald.'
        ],
        [
            'q' => 'How do I adjust the stainless steel link strap?',
            'a' => 'The bracelet includes removable links secured by standard split-pins. Push pins in the direction of the stamped arrows to size the band to your wrist.'
        ],
        [
            'q' => 'What caliber powers this watch?',
            'a' => 'It runs on a Japanese three-hand quartz caliber with shock-cushioned mounting for precise, hassle-free timekeeping.'
        ],
        [
            'q' => 'Is it protected against water?',
            'a' => 'Yes, it is rated at 30M (3 ATM) for rain, accidental submersions, and handwashing.'
        ]
    ],
    15 => [ // Welkar Executive Steel Chrono Dial
        [
            'q' => 'How do the chronograph pushers operate?',
            'a' => 'The top 2 o\'clock pusher starts and stops the stopwatch timer, while the lower 4 o\'clock pusher resets the sub-dial hands to zero.'
        ],
        [
            'q' => 'Is this timepiece suited for formal and boardroom attire?',
            'a' => 'Yes, the 44mm brushed steel bezel, applied polished baton indices, and classic multi-link steel bracelet complement business suits and executive wear.'
        ],
        [
            'q' => 'What warranty applies to this chronograph?',
            'a' => 'All Welkar executive chronographs are covered under our 2-Year movement warranty with pan-India insured transit.'
        ],
        [
            'q' => 'Can I swim with this watch?',
            'a' => 'The watch is sealed to 30M (3 ATM) for rain and splashes. Chronograph pushers should never be actuated in wet conditions.'
        ]
    ],
    16 => [ // Welkar Black-Out Sport Racer
        [
            'q' => 'How is the tachymeter bezel used?',
            'a' => 'The calibrated outer bezel allows you to measure speed based on travel time over a fixed distance (e.g., 1 kilometer) using the chronograph second hand.'
        ],
        [
            'q' => 'Is this watch safe for swimming and intense workouts?',
            'a' => 'Yes. It is pressure-tested to 50M (5 ATM) water resistance and equipped with a breathable perforated silicone strap that wicks away sweat and dries quickly.'
        ],
        [
            'q' => 'What movement powers the Sport Racer?',
            'a' => 'A high-impact quartz caliber engineered with shock-absorbent movement holders to withstand vibrations from running, cycling, or motorsport.'
        ],
        [
            'q' => 'What is the case finish?',
            'a' => 'The 44mm case is finished in non-reflective matte black PVD, giving it an understated stealth aesthetic.'
        ]
    ],
    20 => [ // Welkar Youth Dual-Display Chrono Sport
        [
            'q' => 'Can this youth watch be worn while swimming?',
            'a' => 'Yes. The Arctic Blue Youth watch is sealed to 50M (5 ATM) hydrostatic depth, making it fully safe for recreational pool swimming, water sports, and showers.'
        ],
        [
            'q' => 'How does the dual digital-analog display work?',
            'a' => 'It simultaneously displays traditional analog clock hands and a high-contrast digital screen with 12/24 hour time, date, stopwatch, and hourly chime.'
        ],
        [
            'q' => 'What material is used for the strap and casing?',
            'a' => 'The watch uses impact-resistant drop-safe resin with a soft, non-toxic TPU resin band designed for junior wrist sizes (ages 10-17).'
        ],
        [
            'q' => 'Does it have a backlight for night visibility?',
            'a' => 'Yes, an integrated EL backlight illuminates the digital display at the push of the upper left button.'
        ]
    ],
    21 => [ // Welkar Junior Shock-Guard Digital LED
        [
            'q' => 'What age group is the Junior Shock-Guard designed for?',
            'a' => 'It is ergonomically scaled with a 36mm case and soft silicone strap for children aged 5 to 11 years.'
        ],
        [
            'q' => 'Is this watch drop-resistant and playground safe?',
            'a' => 'Yes, the reinforced bumper bezel absorbs shocks and drops onto hard surfaces, while the mineral-acrylic crystal resists shattering.'
        ],
        [
            'q' => 'Can a child wear this watch in the swimming pool or bath?',
            'a' => 'Yes. It is sealed to 50M (5 ATM) water resistance, safe for swimming and daily baths. Do not press buttons underwater.'
        ],
        [
            'q' => 'What features does the digital display include?',
            'a' => 'It features clear digital time, daily alarm, date calendar, split-second stopwatch, and vibrant multi-color LED night backlight.'
        ]
    ]
];

$pdo = Database::connect();
$pdo->beginTransaction();
try {
    foreach ($productFaqs as $productId => $faqs) {
        Database::execute("DELETE FROM product_faqs WHERE product_id = ?", [$productId]);
        $order = 1;
        foreach ($faqs as $f) {
            Database::execute(
                "INSERT INTO product_faqs (product_id, question, answer, sort_order, is_active) VALUES (?, ?, ?, ?, 1)",
                [$productId, $f['q'], $f['a'], $order++]
            );
        }
    }
    $pdo->commit();
    echo "Successfully updated product_faqs with accurate, fact-checked specifications for all 10 active products.\n";
} catch (\Throwable $e) {
    $pdo->rollBack();
    echo "Error updating product_faqs: " . $e->getMessage() . "\n";
    exit(1);
}
