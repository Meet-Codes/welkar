<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');
require_once __DIR__ . '/../app/Support/helpers.php';

use App\Support\Database;

$faqContent = <<<HTML
<h2>Frequently Asked Questions</h2>
<p class="text-muted">Find answers to commonly asked questions about WELKAR, our founding story, timepiece specifications, and store policies.</p>

<div class="accordion my-4" id="welkarFaqAccordion">
  <div class="accordion-item border rounded mb-3">
    <h3 class="accordion-header" id="faqHeading1">
      <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
        What is WELKAR?
      </button>
    </h3>
    <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1">
      <div class="accordion-body text-secondary" style="line-height: 1.8;">
        WELKAR is a watch brand founded on the principles of thoughtful design, technical balance, and customer trust. We offer timepieces that harmonize distinctive visual character with practical utility, clear specifications, and accessible elegance.
      </div>
    </div>
  </div>

  <div class="accordion-item border rounded mb-3">
    <h3 class="accordion-header" id="faqHeading2">
      <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
        When was WELKAR founded?
      </button>
    </h3>
    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2">
      <div class="accordion-body text-secondary" style="line-height: 1.8;">
        WELKAR was established in <strong>2002</strong>. Over two decades of history guide our current brand identity and reinforce our ongoing commitment to customer trust.
      </div>
    </div>
  </div>

  <div class="accordion-item border rounded mb-3">
    <h3 class="accordion-header" id="faqHeading3">
      <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
        Who founded WELKAR?
      </button>
    </h3>
    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3">
      <div class="accordion-body text-secondary" style="line-height: 1.8;">
        WELKAR was founded by two partners bringing complementary perspectives together:
        <ul>
          <li><strong>Mr. Sumit Singh:</strong> Associated with the artistic outlook, representing creative vision, visual character, and attention to detailing.</li>
          <li><strong>Mr. Nevil Sharma:</strong> Associated with the technical outlook, representing problem-solving, structural balance, and technical thinking.</li>
        </ul>
        Learn more on our <a href="/about" class="text-decoration-underline fw-semibold">About Us page</a>.
      </div>
    </div>
  </div>

  <div class="accordion-item border rounded mb-3">
    <h3 class="accordion-header" id="faqHeading4">
      <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
        What does the WELKAR tagline mean?
      </button>
    </h3>
    <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4">
      <div class="accordion-body text-secondary" style="line-height: 1.8;">
        Our official tagline is <em>&ldquo;Precision, Heritage, and Trust—Since 2002.&rdquo;</em>
        <ul>
          <li><strong>Precision:</strong> Meticulous attention to dial proportion, finish, and accurate product specifications.</li>
          <li><strong>Heritage:</strong> Respect for our journey that began in 2002.</li>
          <li><strong>Trust:</strong> Honest communication, transparent pricing, insured transit, and customer-first service.</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="accordion-item border rounded mb-3">
    <h3 class="accordion-header" id="faqHeading5">
      <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
        How can I contact WELKAR?
      </button>
    </h3>
    <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5">
      <div class="accordion-body text-secondary" style="line-height: 1.8;">
        You can reach our team directly at <strong>welkarstore@gmail.com</strong> for questions about watches, order tracking, and after-sales support.
      </div>
    </div>
  </div>

  <div class="accordion-item border rounded mb-3">
    <h3 class="accordion-header" id="faqHeading6">
      <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
        Where can I read the company's policies?
      </button>
    </h3>
    <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6">
      <div class="accordion-body text-secondary" style="line-height: 1.8;">
        Our official store policies are publicly available at:
        <ul>
          <li><a href="/page/warranty" class="text-decoration-underline">Official Warranty Terms</a></li>
          <li><a href="/page/returns-exchanges" class="text-decoration-underline">7-Day Return &amp; Exchange Policy</a></li>
          <li><a href="/page/shipping-policy" class="text-decoration-underline">Shipping, Delivery &amp; COD Guidelines</a></li>
          <li><a href="/page/privacy-policy" class="text-decoration-underline">Privacy Policy</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
HTML;

Database::execute(
    "INSERT INTO content_pages (slug, locale, title, content_html, meta_title, meta_description, is_published)
     VALUES (?, ?, ?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE title=VALUES(title), content_html=VALUES(content_html), meta_title=VALUES(meta_title), meta_description=VALUES(meta_description), is_published=VALUES(is_published)",
    [
        'faqs',
        'en',
        'Frequently Asked Questions | WELKAR',
        $faqContent,
        'Frequently Asked Questions | WELKAR Watch Co.',
        'Official answers about WELKAR, founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, including warranty, returns, and support.',
        1
    ]
);

echo "FAQs page updated in content_pages successfully!\n";
