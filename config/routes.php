<?php

use App\Support\Router;
use App\Middleware\CsrfMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminAuthMiddleware;

// Storefront Controllers
use App\Controllers\Storefront\HomeController;
use App\Controllers\Storefront\CatalogController;
use App\Controllers\Storefront\CollectionController;
use App\Controllers\Storefront\ProductController;
use App\Controllers\Storefront\WishlistController;
use App\Controllers\Storefront\CartController;
use App\Controllers\Storefront\CheckoutController;
use App\Controllers\Storefront\PageController;
use App\Controllers\Storefront\LocaleController;
use App\Controllers\Storefront\SupportController;

// Auth Controllers
use App\Controllers\Auth\CustomerAuthController;
use App\Controllers\Auth\AdminAuthController;

// Admin Controllers
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\PaymentController;
use App\Controllers\Admin\SupportTicketController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\ReviewController as AdminReviewController;
use App\Controllers\Admin\PageController as AdminPageController;
use App\Controllers\Admin\ImportController;
use App\Controllers\Admin\AnalyticsController;
use App\Controllers\Admin\MarketingController;
use App\Controllers\Admin\CustomerController;
use App\Controllers\Admin\SettingController;
use App\Controllers\Admin\CompanyProfileController;
use App\Controllers\Admin\BrandController;
use App\Controllers\Admin\BannerController;
use App\Controllers\Admin\StaffController;
use App\Controllers\Admin\AuditLogController;

// Webhooks
use App\Controllers\Webhooks\RazorpayWebhookController;

return function (Router $router) {
    // 1. Storefront Routes
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/catalog', [CatalogController::class, 'index']);
    $router->get('/search', [CatalogController::class, 'index']);
    $router->get('/brands', [CatalogController::class, 'brands']);
    $router->get('/brand/{slug}', [CatalogController::class, 'brand']);
    $router->get('/collections/{slug}', [CollectionController::class, 'show']);
    $router->get('/product/{slug}', [ProductController::class, 'show']);
    $router->post('/product/{slug}/review', [ProductController::class, 'submitReview'], [CsrfMiddleware::class]);
    $router->post('/api/reviews/{id}/vote', [ProductController::class, 'voteHelpful'], [CsrfMiddleware::class]);
    $router->get('/track-order', [PageController::class, 'trackOrder']);
    $router->get('/about', [PageController::class, 'about']);
    $router->get('/about-us', [PageController::class, 'about']);
    $router->get('/page/{slug}', [PageController::class, 'show']);
    $router->get('/lang/{locale}', [LocaleController::class, 'switch']);
    $router->get('/sitemap.xml', [PageController::class, 'sitemap']);
    $router->get('/robots.txt', [PageController::class, 'robots']);
    $router->get('/api/search/autocomplete', [CatalogController::class, 'autocomplete']);

    // Wishlist Routes
    $router->get('/wishlist', [WishlistController::class, 'index']);
    $router->post('/wishlist/toggle', [WishlistController::class, 'toggle'], [CsrfMiddleware::class]);
    $router->post('/wishlist/remove', [WishlistController::class, 'remove'], [CsrfMiddleware::class]);
    $router->post('/wishlist/move-to-cart', [WishlistController::class, 'moveToCart'], [CsrfMiddleware::class]);

    // Customer Support Chat & Ticket Helpdesk
    $router->post('/api/support/ticket', [SupportController::class, 'createTicket']);
    $router->get('/api/support/ticket/{ticketNumber}', [SupportController::class, 'getTicket']);
    $router->post('/api/support/ticket/{ticketNumber}/message', [SupportController::class, 'sendMessage']);

    // 2. Shopping Bag & Checkout
    $router->get('/cart', [CartController::class, 'index']);
    $router->post('/cart/add', [CartController::class, 'add'], [CsrfMiddleware::class]);
    $router->post('/cart/update', [CartController::class, 'update'], [CsrfMiddleware::class]);
    $router->post('/cart/remove', [CartController::class, 'remove'], [CsrfMiddleware::class]);
    $router->post('/cart/apply-coupon', [CartController::class, 'applyCoupon'], [CsrfMiddleware::class]);
    $router->post('/cart/remove-coupon', [CartController::class, 'removeCoupon'], [CsrfMiddleware::class]);

    $router->get('/checkout', [CheckoutController::class, 'index']);
    $router->post('/checkout/process', [CheckoutController::class, 'process'], [CsrfMiddleware::class]);
    $router->post('/checkout/verify-payment', [CheckoutController::class, 'verifyPayment'], [CsrfMiddleware::class]);
    $router->get('/order/success/{order_number}', [CheckoutController::class, 'success']);

    // 3. Customer Authentication & Account
    $router->get('/account/login', [CustomerAuthController::class, 'showLogin']);
    $router->post('/account/email-auth', [CustomerAuthController::class, 'emailAuth'], [CsrfMiddleware::class]);
    $router->post('/account/send-otp', [CustomerAuthController::class, 'sendOtp'], [CsrfMiddleware::class]);
    $router->post('/account/verify-otp', [CustomerAuthController::class, 'verifyOtp'], [CsrfMiddleware::class]);
    $router->get('/account', [CustomerAuthController::class, 'dashboard'], [AuthMiddleware::class]);
    $router->post('/account/orders/cancel', [CustomerAuthController::class, 'cancelOrder'], [AuthMiddleware::class, CsrfMiddleware::class]);
    $router->get('/account/invoice/{order_number}', [CustomerAuthController::class, 'downloadInvoice'], [AuthMiddleware::class]);
    $router->get('/account/logout', [CustomerAuthController::class, 'logout']);

    // 4. Admin Authentication
    $router->get('/admin/login', [AdminAuthController::class, 'showLogin']);
    $router->post('/admin/login', [AdminAuthController::class, 'login'], [CsrfMiddleware::class]);
    $router->get('/admin/mfa-verify', [AdminAuthController::class, 'showMfa']);
    $router->post('/admin/mfa-verify', [AdminAuthController::class, 'verifyMfa'], [CsrfMiddleware::class]);
    $router->get('/admin/logout', [AdminAuthController::class, 'logout']);

    // 5. Protected Admin Console
    $adminGuard = [AdminAuthMiddleware::class];
    $adminPostGuard = [AdminAuthMiddleware::class, CsrfMiddleware::class];

    $router->get('/admin', [DashboardController::class, 'index'], $adminGuard);
    $router->get('/admin/orders', [OrderController::class, 'index'], $adminGuard);
    $router->get('/admin/orders/{id}', [OrderController::class, 'show'], $adminGuard);
    $router->post('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'], $adminPostGuard);
    $router->post('/admin/orders/{id}/tracking', [OrderController::class, 'addTracking'], $adminPostGuard);
    $router->post('/admin/orders/{id}/refund', [OrderController::class, 'recordRefund'], $adminPostGuard);
    $router->post('/admin/orders/{id}/return', [OrderController::class, 'processReturn'], $adminPostGuard);
    $router->get('/admin/orders/{id}/invoice', [OrderController::class, 'invoice'], $adminGuard);
    $router->post('/admin/orders/{id}/payment', [OrderController::class, 'updatePayment'], $adminPostGuard);

    // Payments & Transactions Management
    $router->get('/admin/payments', [PaymentController::class, 'index'], $adminGuard);
    $router->get('/admin/payments/{id}', [PaymentController::class, 'show'], $adminGuard);
    $router->post('/admin/payments/{id}/status', [PaymentController::class, 'updateStatus'], $adminPostGuard);

    // Customer Support Helpdesk & Chat Tickets
    $router->get('/admin/support', [SupportTicketController::class, 'index'], $adminGuard);
    $router->get('/admin/support/{id}', [SupportTicketController::class, 'show'], $adminGuard);
    $router->post('/admin/support/{id}/reply', [SupportTicketController::class, 'reply'], $adminPostGuard);
    $router->post('/admin/support/{id}/status', [SupportTicketController::class, 'updateStatus'], $adminPostGuard);
    $router->post('/admin/support/{id}/notes', [SupportTicketController::class, 'updateNotes'], $adminPostGuard);

    $router->get('/admin/products', [AdminProductController::class, 'index'], $adminGuard);
    $router->get('/admin/products/create', [AdminProductController::class, 'create'], $adminGuard);
    $router->post('/admin/products/store', [AdminProductController::class, 'store'], $adminPostGuard);
    $router->get('/admin/products/{id}/edit', [AdminProductController::class, 'edit'], $adminGuard);
    $router->post('/admin/products/{id}/update', [AdminProductController::class, 'update'], $adminPostGuard);
    $router->post('/admin/products/{id}/delete', [AdminProductController::class, 'destroy'], $adminPostGuard);
    $router->post('/admin/products/{id}/adjust-stock', [AdminProductController::class, 'adjustStock'], $adminPostGuard);
    $router->post('/admin/products/{id}/images/upload', [AdminProductController::class, 'uploadImage'], $adminPostGuard);
    $router->post('/admin/products/{id}/images/{imageId}/update', [AdminProductController::class, 'updateImage'], $adminPostGuard);
    $router->post('/admin/products/{id}/images/{imageId}/primary', [AdminProductController::class, 'setPrimaryImage'], $adminPostGuard);
    $router->post('/admin/products/{id}/images/{imageId}/delete', [AdminProductController::class, 'deleteImage'], $adminPostGuard);

    // Specifications CRUD
    $router->post('/admin/products/{id}/core-specs/update', [AdminProductController::class, 'updateCoreSpecifications'], $adminPostGuard);
    $router->post('/admin/products/{id}/specifications/save', [AdminProductController::class, 'saveSpecification'], $adminPostGuard);
    $router->post('/admin/products/{id}/specifications/{specId}/delete', [AdminProductController::class, 'deleteSpecification'], $adminPostGuard);

    // Hotspots CRUD
    $router->post('/admin/products/{id}/hotspots/save', [AdminProductController::class, 'saveHotspot'], $adminPostGuard);
    $router->post('/admin/products/{id}/hotspots/{hotspotId}/delete', [AdminProductController::class, 'deleteHotspot'], $adminPostGuard);

    // A+ Content Modules (Banner, Stories, Lifestyle)
    $router->post('/admin/products/{id}/modules/banner', [AdminProductController::class, 'saveBannerModule'], $adminPostGuard);
    $router->post('/admin/products/{id}/modules/stories/save', [AdminProductController::class, 'saveStoryModule'], $adminPostGuard);
    $router->post('/admin/products/{id}/modules/story/save', [AdminProductController::class, 'saveStoryModule'], $adminPostGuard);
    $router->post('/admin/products/{id}/modules/stories/{storyIndex}/delete', [AdminProductController::class, 'deleteStoryModule'], $adminPostGuard);
    $router->post('/admin/products/{id}/modules/story/{storyIndex}/delete', [AdminProductController::class, 'deleteStoryModule'], $adminPostGuard);
    $router->post('/admin/products/{id}/modules/lifestyle/save', [AdminProductController::class, 'saveLifestyleModule'], $adminPostGuard);
    $router->post('/admin/products/{id}/modules/lifestyle/{photoIndex}/delete', [AdminProductController::class, 'deleteLifestyleModule'], $adminPostGuard);

    // FAQs CRUD
    $router->post('/admin/products/{id}/faqs/save', [AdminProductController::class, 'saveFaq'], $adminPostGuard);
    $router->post('/admin/products/{id}/faqs/{faqId}/delete', [AdminProductController::class, 'deleteFaq'], $adminPostGuard);

    // Reviews Add, Edit, Delete
    $router->post('/admin/products/{id}/reviews/save', [AdminProductController::class, 'saveReview'], $adminPostGuard);
    $router->post('/admin/products/{id}/reviews/{reviewId}/update', [AdminProductController::class, 'updateReview'], $adminPostGuard);
    $router->post('/admin/products/{id}/reviews/{reviewId}/delete', [AdminProductController::class, 'deleteReview'], $adminPostGuard);

    $router->get('/admin/categories', [CategoryController::class, 'index'], $adminGuard);
    $router->post('/admin/categories/store', [CategoryController::class, 'storeCategory'], $adminPostGuard);
    $router->post('/admin/categories/{id}/update', [CategoryController::class, 'updateCategory'], $adminPostGuard);
    $router->post('/admin/categories/{id}/delete', [CategoryController::class, 'deleteCategory'], $adminPostGuard);

    // Brand Management
    $router->get('/admin/brands', [BrandController::class, 'index'], $adminGuard);
    $router->post('/admin/brands/store', [BrandController::class, 'store'], $adminPostGuard);
    $router->post('/admin/brands/{id}/update', [BrandController::class, 'update'], $adminPostGuard);
    $router->post('/admin/brands/{id}/delete', [BrandController::class, 'destroy'], $adminPostGuard);

    // Hero Banners Management
    $router->get('/admin/banners', [BannerController::class, 'index'], $adminGuard);
    $router->post('/admin/banners/store', [BannerController::class, 'store'], $adminPostGuard);
    $router->post('/admin/banners/{id}/update', [BannerController::class, 'update'], $adminPostGuard);
    $router->post('/admin/banners/{id}/delete', [BannerController::class, 'destroy'], $adminPostGuard);

    // Staff Users & Role Governance
    $router->get('/admin/staff', [StaffController::class, 'index'], $adminGuard);
    $router->post('/admin/staff/store', [StaffController::class, 'store'], $adminPostGuard);
    $router->post('/admin/staff/{id}/toggle', [StaffController::class, 'toggleStatus'], $adminPostGuard);

    // Audit Trail
    $router->get('/admin/audit-logs', [AuditLogController::class, 'index'], $adminGuard);

    // Reviews Moderation
    $router->get('/admin/reviews', [AdminReviewController::class, 'index'], $adminGuard);
    $router->post('/admin/reviews/{id}/status', [AdminReviewController::class, 'updateStatus'], $adminPostGuard);
    $router->post('/admin/reviews/{id}/update', [AdminReviewController::class, 'update'], $adminPostGuard);
    $router->post('/admin/reviews/{id}/delete', [AdminReviewController::class, 'destroy'], $adminPostGuard);

    // Content Pages CMS
    $router->get('/admin/pages', [AdminPageController::class, 'index'], $adminGuard);
    $router->get('/admin/pages/{id}/edit', [AdminPageController::class, 'edit'], $adminGuard);
    $router->post('/admin/pages/{id}/update', [AdminPageController::class, 'update'], $adminPostGuard);

    $router->get('/admin/importer', [ImportController::class, 'index'], $adminGuard);
    $router->get('/admin/importer/template', [ImportController::class, 'downloadTemplate'], $adminGuard);
    $router->post('/admin/importer/upload', [ImportController::class, 'upload'], $adminPostGuard);

    $router->get('/admin/analytics', [AnalyticsController::class, 'index'], $adminGuard);
    $router->get('/admin/marketing', [MarketingController::class, 'index'], $adminGuard);
    $router->post('/admin/marketing/coupons', [MarketingController::class, 'storeCoupon'], $adminPostGuard);
    $router->post('/admin/marketing/abandoned-carts/{id}/recover', [MarketingController::class, 'generateRecovery'], $adminPostGuard);
    $router->get('/admin/customers', [CustomerController::class, 'index'], $adminGuard);
    $router->get('/admin/settings', [SettingController::class, 'index'], $adminGuard);
    $router->post('/admin/settings/update', [SettingController::class, 'update'], $adminPostGuard);

    // Company Profile & Brand Storytelling
    $router->get('/admin/company-profile', [CompanyProfileController::class, 'index'], $adminGuard);
    $router->post('/admin/company-profile', [CompanyProfileController::class, 'updateProfile'], $adminPostGuard);
    $router->post('/admin/company-profile/founders', [CompanyProfileController::class, 'saveFounder'], $adminPostGuard);
    $router->post('/admin/company-profile/milestones', [CompanyProfileController::class, 'saveMilestone'], $adminPostGuard);
    $router->post('/admin/company-profile/milestones/{id}/delete', [CompanyProfileController::class, 'deleteMilestone'], $adminPostGuard);

    // 6. External Webhooks
    $router->post('/webhooks/razorpay', [RazorpayWebhookController::class, 'handle']);
};
