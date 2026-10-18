<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Edit Watch: <?= e($product['name']) ?></h3>
        <p class="text-muted small m-0">Base SKU: <code><?= e($product['base_sku']) ?></code> &bull; Model: <?= e($product['model_number'] ?? 'N/A') ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="/product/<?= e($product['slug']) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Preview on Storefront
        </a>
        <form action="/admin/products/<?= (int)$product['id'] ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="d-inline m-0" onsubmit="return confirm('Are you sure you want to delete watch \'<?= e(addslashes($product['name'])) ?>\'? This will permanently remove its stock and media.');">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                <i class="bi bi-trash me-1"></i> Delete Watch
            </button>
        </form>
        <a href="/admin/products" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Product Information & Live Watch Animation Calibration -->
    <div class="col-lg-7">
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3">Watch Details & Pricing</h5>
            <form id="editProductForm" action="/admin/products/<?= (int)$product['id'] ?>/update?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Watch Name *</label>
                        <input type="text" name="name" class="form-control" value="<?= e($product['name']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Brand</label>
                        <input type="text" class="form-control bg-light" value="<?= e($brands[array_search($product['brand_id'], array_column($brands, 'id'))]['name'] ?? 'Welkar') ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">None / Unassigned</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>" <?= ((int)($assignedCategoryId ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Selling Price (₹) *</label>
                        <input type="number" step="0.01" name="base_price" class="form-control" value="<?= (float)$product['base_price'] ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Cost Price (₹)</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" value="<?= $product['cost_price'] !== null ? (float)$product['cost_price'] : '' ?>" placeholder="Optional">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Compare-At Price (MRP ₹)</label>
                        <input type="number" step="0.01" name="compare_at_price" class="form-control" value="<?= $product['compare_at_price'] !== null ? (float)$product['compare_at_price'] : '' ?>" placeholder="Optional">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Dial Type</label>
                        <input type="text" name="dial_type" class="form-control" value="<?= e($product['dial_type']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Publish Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" <?= (bool)$product['is_active'] ? 'selected' : '' ?>>Published & Active</option>
                            <option value="0" <?= !(bool)$product['is_active'] ? 'selected' : '' ?>>Archived / Hidden</option>
                        </select>
                    </div>

                    <!-- Fast Photo Upload in Main Form -->
                    <div class="col-12 border-top pt-3 mt-3">
                        <h6 class="fw-bold mb-2"><i class="bi bi-camera me-1"></i>Upload & Attach Watch Photos</h6>
                        <div class="row g-2">
                            <div class="col-md-7">
                                <label class="form-label small text-muted">Upload Photos from Device</label>
                                <input type="file" name="images[]" class="form-control form-control-sm" multiple accept="image/*">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small text-muted">Or Add Photo URL</label>
                                <input type="text" name="image_url" class="form-control form-control-sm" placeholder="https://example.com/photo.jpg">
                            </div>
                            <div class="col-12 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="replace_primary_photo" value="1" id="replacePrimaryMain">
                                    <label class="form-check-label small" for="replacePrimaryMain">Set newly added photo as the primary cover photo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Watch Clock Animation Configuration & Calibration -->
                    <div class="col-12 border-top pt-3 mt-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold m-0"><i class="bi bi-broadcast text-primary me-2"></i>Live Animated Watch Clock Simulation</h6>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="is_live_dial_animated" value="1" id="liveDialToggle" <?= (bool)$product['is_live_dial_animated'] ? 'checked' : '' ?>>
                                <label class="form-check-label small fw-bold" for="liveDialToggle">Enable on Storefront</label>
                            </div>
                        </div>
                        <p class="text-muted small">When enabled, the product hero displays real-time animated clock hands ticking in sync with visitor device time.</p>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Dial Center Pivot X (%)</label>
                                <input type="number" name="dial_center_x" class="form-control form-control-sm" value="<?= (int)($product['dial_center_x'] ?? 50) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Dial Center Pivot Y (%)</label>
                                <input type="number" name="dial_center_y" class="form-control form-control-sm" value="<?= (int)($product['dial_center_y'] ?? 50) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-dark fw-bold px-4">
                            <i class="bi bi-check2 me-1"></i> Save Changes & Update Photos
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Product Media & Photos Gallery Card -->
        <div id="product-images-card" class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0"><i class="bi bi-images text-dark me-2"></i>Product Photos & Gallery</h5>
                <span class="badge bg-light text-dark border"><?= count($images) ?> Photos</span>
            </div>
            <p class="text-muted small mb-3">Manage all imagery for this watch. Upload new photos, edit photo URLs directly, set the featured cover photo, or remove photos.</p>

            <!-- Upload Form -->
            <form id="quickUploadForm" action="/admin/products/<?= (int)$product['id'] ?>/images/upload?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data" class="border rounded p-3 bg-light mb-4">
                <?= csrf_field() ?>
                <h6 class="fw-bold small mb-2"><i class="bi bi-cloud-arrow-up me-1"></i>Quick Upload New Photos</h6>
                <div class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <input type="file" name="images[]" id="editImageUpload" class="form-control form-control-sm" multiple accept="image/*">
                        <div class="form-text small" style="font-size: 11px;">Select one or multiple images (JPG, PNG, WEBP, GIF). Max 64MB total.</div>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="image_url" class="form-control form-control-sm" placeholder="Or enter Image URL">
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                        <div class="form-check m-0">
                            <input class="form-check-input" type="checkbox" name="is_primary" value="1" id="isPrimaryUpload">
                            <label class="form-check-label small fw-semibold" for="isPrimaryUpload">Set as Primary Front Photo</label>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm fw-bold px-3">
                            <i class="bi bi-upload me-1"></i> Upload Photos
                        </button>
                    </div>
                </div>
                <!-- Client-side Preview -->
                <div id="editImagePreviewContainer" class="d-flex flex-wrap gap-2 mt-3" style="display: none !important;"></div>
            </form>

            <!-- Gallery Cards -->
            <?php if (empty($images)): ?>
                <div class="text-center py-4 bg-light rounded border border-dashed">
                    <i class="bi bi-image fs-1 text-muted"></i>
                    <p class="text-muted small mt-2 mb-0">No photos uploaded for this watch yet. Use the upload form above to add your first photo.</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($images as $img): ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="card h-100 border shadow-sm position-relative overflow-hidden">
                            <div style="height: 150px; background: #f8fafc;" class="d-flex align-items-center justify-content-center p-1">
                                <img src="<?= e($img['image_url']) ?>" alt="Watch Photo" class="w-100 h-100 object-fit-contain rounded">
                            </div>
                            <div class="p-2 border-top bg-white">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <?php if ((bool)$img['is_primary']): ?>
                                        <span class="badge bg-success" style="font-size: 10px;"><i class="bi bi-star-fill me-1"></i>Primary</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary" style="font-size: 10px;">Gallery</span>
                                    <?php endif; ?>
                                    <span class="text-muted" style="font-size: 10px;"><?= date('d M Y', strtotime($img['created_at'] ?? 'now')) ?></span>
                                </div>
                                <div class="d-flex gap-1 justify-content-between align-items-center mb-2">
                                    <?php if (!(bool)$img['is_primary']): ?>
                                    <form action="/admin/products/<?= (int)$product['id'] ?>/images/<?= (int)$img['id'] ?>/primary?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 11px;" title="Set as primary photo">
                                            <i class="bi bi-star me-1"></i>Set Primary
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <span class="text-success small fw-bold" style="font-size: 11px;"><i class="bi bi-check-circle-fill me-1"></i>Main Photo</span>
                                    <?php endif; ?>

                                    <div class="d-flex gap-1">
                                        <a href="<?= e($img['image_url']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm py-0 px-1" style="font-size: 11px;" title="View full size">
                                            <i class="bi bi-arrows-fullscreen"></i>
                                        </a>
                                        <form action="/admin/products/<?= (int)$product['id'] ?>/images/<?= (int)$img['id'] ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this watch photo?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 11px;" title="Delete this photo">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Direct Photo URL Editor -->
                                <form action="/admin/products/<?= (int)$product['id'] ?>/images/<?= (int)$img['id'] ?>/update?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="border-top pt-2 mt-2">
                                    <?= csrf_field() ?>
                                    <label class="form-label text-muted" style="font-size: 10px; margin-bottom: 2px;">Edit Photo URL</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="image_url" class="form-control form-control-sm py-0" value="<?= e($img['image_url']) ?>" style="font-size: 11px;" required>
                                        <button type="submit" class="btn btn-outline-dark btn-sm py-0 px-2" style="font-size: 11px;">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('editImageUpload');
        const previewContainer = document.getElementById('editImagePreviewContainer');
        const quickForm = document.getElementById('quickUploadForm');
        const editForm = document.getElementById('editProductForm');

        function formatBytes(bytes) {
            if (!bytes || bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function compressImage(file, maxDimension = 1600, quality = 0.85) {
            return new Promise((resolve) => {
                if (!file.type.startsWith('image/') || file.size < 1.2 * 1024 * 1024) {
                    return resolve(file);
                }
                const img = new Image();
                img.onload = () => {
                    URL.revokeObjectURL(img.src);
                    let { width, height } = img;
                    if (width > maxDimension || height > maxDimension) {
                        if (width > height) {
                            height = Math.round((height * maxDimension) / width);
                            width = maxDimension;
                        } else {
                            width = Math.round((width * maxDimension) / height);
                            height = maxDimension;
                        }
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    canvas.toBlob((blob) => {
                        if (!blob || blob.size >= file.size) {
                            return resolve(file);
                        }
                        const newFile = new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(newFile);
                    }, 'image/jpeg', quality);
                };
                img.onerror = () => resolve(file);
                img.src = URL.createObjectURL(file);
            });
        }

        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function() {
                previewContainer.innerHTML = '';
                const files = Array.from(this.files);

                if (files.length > 0) {
                    previewContainer.style.setProperty('display', 'flex', 'important');
                    files.forEach((file, idx) => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'position-relative border rounded p-1 bg-white shadow-sm';
                            wrapper.style.width = '75px';
                            wrapper.style.height = '75px';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-100 h-100 object-fit-cover rounded';

                            const badge = document.createElement('span');
                            badge.className = 'position-absolute top-0 start-0 badge bg-dark';
                            badge.style.fontSize = '8px';
                            badge.innerText = formatBytes(file.size);

                            wrapper.appendChild(img);
                            wrapper.appendChild(badge);
                            previewContainer.appendChild(wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    previewContainer.style.setProperty('display', 'none', 'important');
                }
            });
        }

        if (quickForm) {
            quickForm.addEventListener('submit', async function(e) {
                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                    const rawFiles = Array.from(fileInput.files);
                    const hasOversized = rawFiles.some(f => f.size > 1.2 * 1024 * 1024);
                    if (hasOversized) {
                        e.preventDefault();
                        const btn = quickForm.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.disabled = true;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Optimizing...';
                        }
                        try {
                            const compressed = await Promise.all(rawFiles.map(f => compressImage(f)));
                            const dt = new DataTransfer();
                            compressed.forEach(f => dt.items.add(f));
                            fileInput.files = dt.files;
                        } catch (err) {
                            console.error(err);
                        }
                        quickForm.submit();
                    }
                }
            });
        }

        if (editForm) {
            const editFileInput = editForm.querySelector('input[type="file"][name="images[]"]');
            editForm.addEventListener('submit', async function(e) {
                if (editFileInput && editFileInput.files && editFileInput.files.length > 0) {
                    const rawFiles = Array.from(editFileInput.files);
                    const hasOversized = rawFiles.some(f => f.size > 1.2 * 1024 * 1024);
                    if (hasOversized) {
                        e.preventDefault();
                        const btn = editForm.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.disabled = true;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Optimizing photos...';
                        }
                        try {
                            const compressed = await Promise.all(rawFiles.map(f => compressImage(f)));
                            const dt = new DataTransfer();
                            compressed.forEach(f => dt.items.add(f));
                            editFileInput.files = dt.files;
                        } catch (err) {
                            console.error(err);
                        }
                        editForm.submit();
                    }
                }
            });
        }
    });
    </script>

    <!-- Right Column: Stock Management & Variants -->
    <div class="col-lg-5">
        <!-- Stock Adjustments -->
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-stack me-2"></i>Inventory Stock Adjustment</h5>
            <p class="text-muted small">Update variant inventory quantities. Every change is logged to the inventory ledger.</p>

            <?php foreach ($variants as $v): ?>
            <div class="border p-3 rounded mb-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong class="d-block small"><?= e($v['variant_name']) ?></strong>
                        <span class="text-muted" style="font-size: 11px;">SKU: <?= e($v['sku']) ?></span>
                    </div>
                    <span class="badge bg-dark fs-6"><?= (int)$v['stock_quantity'] ?> units</span>
                </div>

                <form action="/admin/products/<?= (int)$product['id'] ?>/adjust-stock?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="d-flex gap-2">
                    <?= csrf_field() ?>
                    <input type="hidden" name="variant_id" value="<?= (int)$v['id'] ?>">
                    <input type="number" name="change_qty" class="form-control form-control-sm text-center" placeholder="+/- Qty" style="width: 100px;" required>
                    <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason (e.g. Restock)" required>
                    <button type="submit" class="btn btn-sm btn-dark text-nowrap">Adjust</button>
                </form>
            </div>
            <?php endforeach; ?>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION MANAGEMENT TABS: FULL CUSTOMIZATION FOR ALL STOREFRONT SECTIONS -->
<!-- ========================================================================= -->
<div class="card border shadow-sm mb-5 bg-white" style="border-radius: 8px;">
    <div class="card-header bg-white border-bottom p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold m-0"><i class="bi bi-sliders2 text-primary me-2"></i>Product Page Sections & Content Manager</h5>
            <span class="badge bg-light text-dark border">Sections Marked in Storefront</span>
        </div>
        <p class="text-muted small m-0">Edit, add, or delete text, imagery, and specifications for every section of this product page.</p>

        <!-- Nav Tabs -->
        <ul class="nav nav-pills mt-3 gap-1" id="sectionTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active small fw-bold" id="tab-specs-nav" data-bs-toggle="pill" data-bs-target="#tab-specifications" type="button" role="tab">
                    <i class="bi bi-cpu me-1"></i> 3. Technical Specs
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small fw-bold" id="tab-banner-nav" data-bs-toggle="pill" data-bs-target="#tab-banner" type="button" role="tab">
                    <i class="bi bi-card-image me-1"></i> 4. Editorial Wide Banner
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small fw-bold" id="tab-hotspots-nav" data-bs-toggle="pill" data-bs-target="#tab-hotspots" type="button" role="tab">
                    <i class="bi bi-geo-alt me-1"></i> 5. Anatomy Hotspots
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small fw-bold" id="tab-stories-nav" data-bs-toggle="pill" data-bs-target="#tab-stories" type="button" role="tab">
                    <i class="bi bi-journal-text me-1"></i> 6. Craftsmanship Stories
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small fw-bold" id="tab-lifestyle-nav" data-bs-toggle="pill" data-bs-target="#tab-lifestyle" type="button" role="tab">
                    <i class="bi bi-camera me-1"></i> 7. On The Wrist
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small fw-bold" id="tab-faqs-nav" data-bs-toggle="pill" data-bs-target="#tab-faqs" type="button" role="tab">
                    <i class="bi bi-question-circle me-1"></i> 9. Product FAQs
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small fw-bold" id="tab-reviews-nav" data-bs-toggle="pill" data-bs-target="#tab-reviews" type="button" role="tab">
                    <i class="bi bi-star me-1"></i> 10. Reviews & Ratings
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="sectionTabsContent">
            
            <!-- TAB 1: TECHNICAL SPECIFICATIONS -->
            <div class="tab-pane fade show active" id="tab-specifications" role="tabpanel">
                <div class="row g-4">
                    <!-- Core Specs Form -->
                    <div class="col-lg-6">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="fw-bold mb-3"><i class="bi bi-pencil-square me-1"></i>Core Technical Specs</h6>
                            <form action="/admin/products/<?= (int)$product['id'] ?>/specifications/core?_csrf_token=<?= e(csrf_token()) ?>" method="POST">
                                <?= csrf_field() ?>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Movement</label>
                                        <input type="text" name="movement" class="form-control form-control-sm" value="<?= e($product['movement'] ?? 'Japanese Quartz') ?>" placeholder="e.g. In-House Automatic Calibre">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Case Material</label>
                                        <input type="text" name="case_material" class="form-control form-control-sm" value="<?= e($product['case_material'] ?? '316L Surgical Stainless Steel') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Strap Material</label>
                                        <input type="text" name="strap_material" class="form-control form-control-sm" value="<?= e($product['strap_material'] ?? 'Solid Link Stainless Steel') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Water Resistance</label>
                                        <input type="text" name="water_resistance" class="form-control form-control-sm" value="<?= e($product['water_resistance'] ?? '10 ATM / 100m') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Case Diameter</label>
                                        <input type="text" name="case_diameter" class="form-control form-control-sm" value="<?= e($product['case_diameter'] ?? '41mm') ?>" placeholder="e.g. 41mm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Dial Glass</label>
                                        <input type="text" name="dial_glass" class="form-control form-control-sm" value="<?= e($product['dial_glass'] ?? 'Anti-Reflective Sapphire Crystal') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Lug to Lug</label>
                                        <input type="text" name="lug_to_lug" class="form-control form-control-sm" value="<?= e($product['lug_to_lug'] ?? '48mm') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Case Thickness</label>
                                        <input type="text" name="case_thickness" class="form-control form-control-sm" value="<?= e($product['case_thickness'] ?? '11.8mm') ?>">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-dark btn-sm fw-bold px-3">Update Core Specs</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Custom Additional Specs -->
                    <div class="col-lg-6">
                        <div class="border rounded p-3 bg-white">
                            <h6 class="fw-bold mb-3"><i class="bi bi-list-check me-1"></i>Additional Custom Specifications</h6>
                            
                            <!-- Add Spec Form -->
                            <form action="/admin/products/<?= (int)$product['id'] ?>/specifications/save?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="mb-3 p-2 bg-light rounded">
                                <?= csrf_field() ?>
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <input type="text" name="spec_name" class="form-control form-control-sm" placeholder="Spec Name (e.g. Bezel)" required>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="spec_value" class="form-control form-control-sm" placeholder="Spec Value (e.g. Ceramic 120-Click)" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold">Add</button>
                                    </div>
                                </div>
                            </form>

                            <?php if (empty($specifications)): ?>
                                <p class="text-muted small">No custom specifications added yet. Add additional attributes above.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle small mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Specification</th>
                                                <th>Value</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($specifications as $sp): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= e($sp['spec_name']) ?></td>
                                                <td><?= e($sp['spec_value']) ?></td>
                                                <td class="text-end">
                                                    <form action="/admin/products/<?= (int)$product['id'] ?>/specifications/<?= (int)$sp['id'] ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete specification?');">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-link text-danger p-0" title="Delete Spec"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: EDITORIAL WIDE BANNER -->
            <div class="tab-pane fade" id="tab-banner" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <form action="/admin/products/<?= (int)$product['id'] ?>/modules/banner?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Banner Headline *</label>
                                <input type="text" name="headline" class="form-control" value="<?= e($bannerModule['headline'] ?? 'CALIBRATED FOR HOROLOGICAL DISTINCTION') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Banner Story / Body Text</label>
                                <textarea name="body" class="form-control" rows="3"><?= e($bannerModule['body'] ?? 'Every Welkar watch is assembled by master horologists with hand-finished bevels and rigorous 100-hour chronometric testing.') ?></textarea>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold">Upload Background Photo</label>
                                    <input type="file" name="banner_file" class="form-control form-control-sm" accept="image/*">
                                    <div class="form-text small" style="font-size: 11px;">Recommended size: 1464x600px (~2.44:1 wide banner) or 1920x800px.</div>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold">Or Photo URL</label>
                                    <input type="text" name="image_url" class="form-control form-control-sm" value="<?= e($bannerModule['image_url'] ?? '') ?>" placeholder="/uploads/... or https://...">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark fw-bold px-4">Save Editorial Banner</button>
                        </form>
                    </div>
                    <div class="col-lg-5">
                        <label class="form-label small fw-bold">Current Banner Preview</label>
                        <div class="border rounded p-2 bg-light text-center">
                            <?php $bannerPreview = !empty($bannerModule['image_url']) ? $bannerModule['image_url'] : 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1400&q=80'; ?>
                            <img src="<?= e($bannerPreview) ?>" alt="Banner Preview" class="img-fluid rounded border shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">
                            <div class="small text-muted mt-2">Frame Aspect Ratio: ~2.44:1 Wide Aspect</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ANATOMY HOTSPOTS -->
            <div class="tab-pane fade" id="tab-hotspots" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="fw-bold mb-3"><i class="bi bi-pin-map me-1"></i>Add Interactive Pin</h6>
                            <form action="/admin/products/<?= (int)$product['id'] ?>/hotspots/save?_csrf_token=<?= e(csrf_token()) ?>" method="POST">
                                <?= csrf_field() ?>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Pin Title *</label>
                                    <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. 120-Click Ceramic Bezel" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Pin Description *</label>
                                    <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Laser-etched graduations with BGW9 luminescence." required></textarea>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">X Position (%)</label>
                                        <input type="number" step="0.1" name="x_pos_percent" class="form-control form-control-sm" value="50.0" min="0" max="100">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">Y Position (%)</label>
                                        <input type="number" step="0.1" name="y_pos_percent" class="form-control form-control-sm" value="50.0" min="0" max="100">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-dark btn-sm fw-bold w-100">Add Anatomy Pin</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <h6 class="fw-bold mb-3">Active Interactive Hotspots (<?= count($hotspots) ?>)</h6>
                        <?php if (empty($hotspots)): ?>
                            <div class="p-4 border rounded bg-light text-center text-muted small">
                                No hotspot pins configured yet. Pins allow customers to click callouts over the watch anatomy photo.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive border rounded bg-white">
                                <table class="table table-sm align-middle small mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Pin Title</th>
                                            <th>Coordinates</th>
                                            <th>Description</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($hotspots as $hs): ?>
                                        <tr>
                                            <td class="fw-bold"><?= e($hs['title']) ?></td>
                                            <td><span class="badge bg-secondary-subtle text-secondary"><?= (float)$hs['x_pos_percent'] ?>%, <?= (float)$hs['y_pos_percent'] ?>%</span></td>
                                            <td class="text-truncate" style="max-width: 220px;"><?= e($hs['description']) ?></td>
                                            <td class="text-end">
                                                <form action="/admin/products/<?= (int)$product['id'] ?>/hotspots/<?= (int)$hs['id'] ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this hotspot pin?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- TAB 4: CRAFTSMANSHIP STORIES -->
            <div class="tab-pane fade" id="tab-stories" role="tabpanel">
                <div class="row g-4">
                    <!-- Add Story Form -->
                    <div class="col-lg-5">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-1"></i>Add Craftsmanship Feature Story</h6>
                            <form action="/admin/products/<?= (int)$product['id'] ?>/modules/stories/save?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Eyebrow / Badge Tag</label>
                                    <input type="text" name="eyebrow" class="form-control form-control-sm" placeholder="e.g. COLD-FORGED STEEL">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Story Title *</label>
                                    <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. 72-Hour Calibre Movement" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Story Description *</label>
                                    <textarea name="text" class="form-control form-control-sm" rows="3" placeholder="Crafted with precision escapement..." required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Feature Bullets (1 per line)</label>
                                    <textarea name="bullets" class="form-control form-control-sm" rows="3" placeholder="28,800 vibrations per hour&#10;Glucydur balance wheel&#10;Anti-magnetic silicon hairspring"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Upload Story Photo</label>
                                    <input type="file" name="story_file" class="form-control form-control-sm" accept="image/*">
                                    <div class="form-text small" style="font-size: 11px;">Recommended size: 800x600px (4:3 aspect ratio).</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Or Photo URL</label>
                                    <input type="text" name="image_url" class="form-control form-control-sm" placeholder="/uploads/stories/... or https://...">
                                </div>
                                <button type="submit" class="btn btn-dark btn-sm fw-bold w-100">Add Story Card</button>
                            </form>
                        </div>
                    </div>

                    <!-- Existing Stories List -->
                    <div class="col-lg-7">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold m-0">Alternating Craftsmanship Stories</h6>
                            <span class="badge bg-secondary-subtle text-secondary"><?= count($storiesModule['stories'] ?? []) ?> Active Stories</span>
                        </div>
                        <?php 
                        $storyList = $storiesModule['stories'] ?? [];
                        ?>
                        <?php if (empty($storyList)): ?>
                            <div class="alert alert-light border text-center py-4 text-muted small">
                                <i class="bi bi-journal-text fs-2 d-block mb-2 text-secondary"></i>
                                No craftsmanship stories added yet for this watch. Use the form on the left to add your first story.
                            </div>
                        <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($storyList as $sIdx => $st): ?>
                            <div class="col-12">
                                <div class="card border shadow-sm p-3">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-sm-4 text-center">
                                            <img src="<?= e($st['image_url'] ?? '') ?>" alt="<?= e($st['title'] ?? 'Story') ?>" class="img-fluid rounded border" style="max-height: 120px; object-fit: cover; width: 100%;">
                                            <span class="badge bg-light text-muted border mt-2" style="font-size: 10px;">Frame: 800x600 (4:3)</span>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <div>
                                                    <?php if (!empty($st['eyebrow'])): ?>
                                                        <span class="badge bg-dark-subtle text-dark border me-1" style="font-size: 10px;"><?= e($st['eyebrow']) ?></span>
                                                    <?php endif; ?>
                                                    <h6 class="fw-bold mb-1 d-inline"><?= e($st['title'] ?? '') ?></h6>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn btn-outline-dark btn-sm py-0 px-2" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#editStoryModal<?= $sIdx ?>">
                                                        <i class="bi bi-pencil me-1"></i>Edit
                                                    </button>
                                                    <form action="/admin/products/<?= (int)$product['id'] ?>/modules/stories/<?= $sIdx ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="m-0" onsubmit="return confirm('Delete craftsmanship story \'<?= e(addslashes($st['title'] ?? '')) ?>\'?');">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 11px;"><i class="bi bi-trash"></i> Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <p class="text-muted small mb-2"><?= e($st['text'] ?? '') ?></p>
                                            <?php if (!empty($st['bullets']) && is_array($st['bullets'])): ?>
                                                <ul class="text-muted small ps-3 mb-0">
                                                    <?php foreach ($st['bullets'] as $b): ?>
                                                        <li><?= e($b) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Story Modal -->
                            <div class="modal fade" id="editStoryModal<?= $sIdx ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="/admin/products/<?= (int)$product['id'] ?>/modules/stories/save?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="story_index" value="<?= $sIdx ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Story: <?= e($st['title'] ?? '') ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold">Eyebrow / Badge Tag</label>
                                                        <input type="text" name="eyebrow" class="form-control" value="<?= e($st['eyebrow'] ?? '') ?>" placeholder="e.g. COLD-FORGED STEEL">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold">Story Title *</label>
                                                        <input type="text" name="title" class="form-control" value="<?= e($st['title'] ?? '') ?>" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold">Story Description *</label>
                                                        <textarea name="text" class="form-control" rows="3" required><?= e($st['text'] ?? '') ?></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold">Feature Bullets (1 per line)</label>
                                                        <textarea name="bullets" class="form-control" rows="3"><?= e(implode("\n", $st['bullets'] ?? [])) ?></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold">Upload Replacement Photo</label>
                                                        <input type="file" name="story_file" class="form-control form-control-sm" accept="image/*">
                                                        <div class="form-text small" style="font-size: 11px;">Recommended size: 800x600px (4:3 aspect ratio).</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold">Or Photo URL</label>
                                                        <input type="text" name="image_url" class="form-control form-control-sm" value="<?= e($st['image_url'] ?? '') ?>">
                                                    </div>
                                                    <?php if (!empty($st['image_url'])): ?>
                                                    <div class="col-12">
                                                        <label class="form-label small text-muted">Current Story Photo</label>
                                                        <div>
                                                            <img src="<?= e($st['image_url']) ?>" alt="Story Preview" class="rounded border" style="max-height: 120px; object-fit: cover;">
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-dark fw-bold px-4">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- TAB 5: ON THE WRIST LIFESTYLE GALLERY -->
            <div class="tab-pane fade" id="tab-lifestyle" role="tabpanel">
                <div class="row g-4">
                    <!-- Add Photo Form -->
                    <div class="col-lg-4">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="fw-bold mb-3"><i class="bi bi-camera me-1"></i>Add Lifestyle Shot</h6>
                            <form action="/admin/products/<?= (int)$product['id'] ?>/modules/lifestyle/save?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Upload Lifestyle Photo *</label>
                                    <input type="file" name="lifestyle_file" class="form-control form-control-sm" accept="image/*">
                                    <div class="form-text small" style="font-size: 11px;">Recommended size: 600x600px (1:1 Square) or 600x750px (4:5).</div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Or Photo URL</label>
                                    <input type="text" name="url" class="form-control form-control-sm" placeholder="/uploads/lifestyle/... or https://...">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Caption Title</label>
                                    <input type="text" name="caption" class="form-control form-control-sm" placeholder="e.g. In The Studio">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Subcaption / Detail</label>
                                    <input type="text" name="subcaption" class="form-control form-control-sm" placeholder="e.g. 41mm on 7.0 inch wrist">
                                </div>
                                <button type="submit" class="btn btn-dark btn-sm fw-bold w-100">Add To Lifestyle Gallery</button>
                            </form>
                        </div>
                    </div>

                    <!-- Lifestyle Photos Grid -->
                    <div class="col-lg-8">
                        <h6 class="fw-bold mb-3">Active On-The-Wrist Gallery</h6>
                        <?php 
                        $lifeList = $lifestyleModule['images'] ?? [
                            ['url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=600&q=80', 'caption' => 'Studio Minimal', 'subcaption' => '41mm on 7.1 inch wrist'],
                            ['url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80', 'caption' => 'Café Espresso', 'subcaption' => 'Natural morning light'],
                            ['url' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&q=80', 'caption' => 'Cocktail Hour', 'subcaption' => 'Low-light luminescence']
                        ];
                        ?>
                        <div class="row g-3">
                            <?php foreach ($lifeList as $lIdx => $lf): ?>
                            <div class="col-sm-6 col-md-4">
                                <div class="card h-100 border shadow-sm position-relative overflow-hidden">
                                    <div style="height: 140px; background: #000;">
                                        <img src="<?= e($lf['url'] ?? '') ?>" alt="<?= e($lf['caption'] ?? 'Lifestyle') ?>" class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div class="p-2 bg-white">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="fw-bold small text-truncate me-1"><?= e($lf['caption'] ?? 'Lifestyle Shot') ?></div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-link text-dark p-0" title="Edit" data-bs-toggle="modal" data-bs-target="#editLifeModal<?= $lIdx ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="/admin/products/<?= (int)$product['id'] ?>/modules/lifestyle/<?= $lIdx ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="m-0" onsubmit="return confirm('Delete this lifestyle shot?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-link text-danger p-0" title="Delete"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="text-muted" style="font-size: 11px;"><?= e($lf['subcaption'] ?? '') ?></div>
                                        <div class="badge bg-light text-muted border mt-2" style="font-size: 9px;">Frame: 600x600 (1:1)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Lifestyle Modal -->
                            <div class="modal fade" id="editLifeModal<?= $lIdx ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="/admin/products/<?= (int)$product['id'] ?>/modules/lifestyle/save?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="photo_index" value="<?= $lIdx ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Lifestyle Photo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Caption Title</label>
                                                    <input type="text" name="caption" class="form-control" value="<?= e($lf['caption'] ?? '') ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Subcaption / Detail</label>
                                                    <input type="text" name="subcaption" class="form-control" value="<?= e($lf['subcaption'] ?? '') ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Upload New Photo</label>
                                                    <input type="file" name="lifestyle_file" class="form-control form-control-sm" accept="image/*">
                                                    <div class="form-text small" style="font-size: 11px;">Recommended size: 600x600px (1:1 Square) or 600x750px (4:5).</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Or Photo URL</label>
                                                    <input type="text" name="url" class="form-control" value="<?= e($lf['url'] ?? '') ?>">
                                                </div>
                                                <?php if (!empty($lf['url'])): ?>
                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Current Photo</label>
                                                    <div>
                                                        <img src="<?= e($lf['url']) ?>" alt="Current preview" class="rounded border" style="max-height: 120px; object-fit: cover;">
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-dark fw-bold">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 6: PRODUCT FAQS -->
            <div class="tab-pane fade" id="tab-faqs" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="fw-bold mb-3"><i class="bi bi-question-circle me-1"></i>Add Product FAQ</h6>
                            <form action="/admin/products/<?= (int)$product['id'] ?>/faqs/save?_csrf_token=<?= e(csrf_token()) ?>" method="POST">
                                <?= csrf_field() ?>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Question *</label>
                                    <input type="text" name="question" class="form-control form-control-sm" placeholder="e.g. Is the bracelet adjustable at home?" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Answer *</label>
                                    <textarea name="answer" class="form-control form-control-sm" rows="3" placeholder="Yes, a pin sizing tool is included in every Welkar gift box." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-dark btn-sm fw-bold w-100">Add Question & Answer</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <h6 class="fw-bold mb-3">Product FAQs (<?= count($faqs) ?>)</h6>
                        <?php if (empty($faqs)): ?>
                            <div class="p-4 border rounded bg-light text-center text-muted small">
                                No specific FAQs configured for this product yet. Default storewide FAQs are shown on the storefront.
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($faqs as $fq): ?>
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <strong class="small"><i class="bi bi-chat-left-dots text-primary me-2"></i><?= e($fq['question']) ?></strong>
                                        <form action="/admin/products/<?= (int)$product['id'] ?>/faqs/<?= (int)$fq['id'] ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="m-0" onsubmit="return confirm('Delete this FAQ?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                    <p class="text-muted small m-0 ps-4"><?= e($fq['answer']) ?></p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- TAB 7: CUSTOMER REVIEWS & RATINGS -->
            <div class="tab-pane fade" id="tab-reviews" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="border rounded p-3 bg-light shadow-sm">
                            <h6 class="fw-bold mb-3"><i class="bi bi-star-fill text-warning me-1"></i>Add Verified Customer Review</h6>
                            <form action="/admin/products/<?= (int)$product['id'] ?>/reviews/save?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="row g-2 mb-2">
                                    <div class="col-7">
                                        <label class="form-label small fw-semibold">Reviewer Name *</label>
                                        <input type="text" name="author_name" class="form-control form-control-sm" placeholder="e.g. Vikram S." required>
                                    </div>
                                    <div class="col-5">
                                        <label class="form-label small fw-semibold">Rating</label>
                                        <select name="rating" class="form-select form-select-sm">
                                            <option value="5" selected>5 Stars (★★★★★)</option>
                                            <option value="4">4 Stars (★★★★☆)</option>
                                            <option value="3">3 Stars (★★★☆☆)</option>
                                            <option value="2">2 Stars (★★☆☆☆)</option>
                                            <option value="1">1 Star (★☆☆☆☆)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Review Title</label>
                                    <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Incredible finishing for the price">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Review Comment *</label>
                                    <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Detailed testimonial from collector..." required></textarea>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">Reviewer Photo</label>
                                        <input type="file" name="avatar_file" class="form-control form-control-sm" accept="image/*">
                                        <small class="text-muted" style="font-size: 10px;">Leave blank for First Letter logo.</small>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">Received Watch Photo</label>
                                        <input type="file" name="review_image" class="form-control form-control-sm" accept="image/*">
                                        <small class="text-muted" style="font-size: 10px;">Wristshot / unboxing photo.</small>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">Status</label>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="approved" selected>Approved (Published)</option>
                                            <option value="pending">Pending Moderation</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-6 d-flex align-items-center pt-3">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="checkbox" name="is_verified_purchase" value="1" id="add_rv_verified" checked>
                                            <label class="form-check-label small fw-semibold" for="add_rv_verified">Verified Buyer</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-dark btn-sm fw-bold w-100">Add Verified Review</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <h6 class="fw-bold mb-3">Customer Reviews & Testimonials (<?= count($reviews) ?>)</h6>
                        <?php if (empty($reviews)): ?>
                            <div class="p-4 border rounded bg-light text-center text-muted small">
                                No reviews logged yet. You can add curated verified customer reviews using the form.
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($reviews as $rv): 
                                    $rvInitial = strtoupper(mb_substr(trim($rv['author_name'] ?: 'V'), 0, 1));
                                    $rvWristshots = !empty($rv['images_json']) ? json_decode($rv['images_json'], true) : [];
                                ?>
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($rv['avatar_url'])): ?>
                                                <img src="<?= e($rv['avatar_url']) ?>" alt="<?= e($rv['author_name']) ?>" class="rounded-circle border" style="width: 34px; height: 34px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 34px; height: 34px; background: linear-gradient(135deg, #0A1128, #1E293B); border: 1px solid rgba(223,183,92,0.4); color: #DFB75C; font-size: 13px;">
                                                    <?= e($rvInitial) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <strong class="small d-block text-dark"><?= e($rv['author_name']) ?></strong>
                                                <div class="d-flex align-items-center gap-1">
                                                    <?php if (!empty($rv['is_verified_purchase'])): ?>
                                                        <span class="badge bg-success" style="font-size: 9px;"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                                                    <?php endif; ?>
                                                    <?php if (($rv['status'] ?? 'approved') === 'approved'): ?>
                                                        <span class="badge bg-success-subtle text-success" style="font-size: 9px;">Approved</span>
                                                    <?php elseif (($rv['status'] ?? '') === 'pending'): ?>
                                                        <span class="badge bg-warning-subtle text-warning" style="font-size: 9px;">Pending Moderation</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger-subtle text-danger" style="font-size: 9px;">Rejected</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-warning small"><?= str_repeat('★', (int)$rv['rating']) . str_repeat('☆', 5 - (int)$rv['rating']) ?></span>
                                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#editReviewModal<?= (int)$rv['id'] ?>">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </button>
                                            <form action="/admin/products/<?= (int)$product['id'] ?>/reviews/<?= (int)$rv['id'] ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="m-0" onsubmit="return confirm('Delete this review permanently?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 11px;"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if (!empty($rv['title'])): ?>
                                        <div class="fw-semibold small text-dark mb-1"><?= e($rv['title']) ?></div>
                                    <?php endif; ?>
                                    <p class="text-muted small m-0 mb-2"><?= e($rv['comment']) ?></p>

                                    <?php if (!empty($rvWristshots) && is_array($rvWristshots)): ?>
                                    <div class="d-flex gap-2 align-items-center pt-1 border-top">
                                        <span class="text-muted" style="font-size: 10px;"><i class="bi bi-camera me-1"></i>Received watch photo:</span>
                                        <?php foreach ($rvWristshots as $wPhoto): ?>
                                            <a href="<?= e($wPhoto) ?>" target="_blank">
                                                <img src="<?= e($wPhoto) ?>" class="rounded border" style="width: 44px; height: 44px; object-fit: cover;" alt="Wristshot">
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                    <div class="text-end text-muted" style="font-size: 10px;"><?= date('d M Y, h:i A', strtotime($rv['created_at'] ?? 'now')) ?></div>
                                </div>

                                <!-- EDIT REVIEW MODAL -->
                                <div class="modal fade" id="editReviewModal<?= (int)$rv['id'] ?>" tabindex="-1" aria-labelledby="editReviewModalLabel<?= (int)$rv['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold" id="editReviewModalLabel<?= (int)$rv['id'] ?>">Edit Review #<?= (int)$rv['id'] ?></h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="/admin/products/<?= (int)$product['id'] ?>/reviews/<?= (int)$rv['id'] ?>/update?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                                                <?= csrf_field() ?>
                                                <div class="modal-body">
                                                    <div class="row g-2 mb-2">
                                                        <div class="col-7">
                                                            <label class="form-label small fw-semibold">Reviewer Name *</label>
                                                            <input type="text" name="author_name" class="form-control form-control-sm" value="<?= e($rv['author_name']) ?>" required>
                                                        </div>
                                                        <div class="col-5">
                                                            <label class="form-label small fw-semibold">Rating</label>
                                                            <select name="rating" class="form-select form-select-sm">
                                                                <?php for($star=5; $star>=1; $star--): ?>
                                                                    <option value="<?= $star ?>" <?= (int)$rv['rating'] === $star ? 'selected' : '' ?>><?= $star ?> Stars (<?= str_repeat('★', $star) ?>)</option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row g-2 mb-2">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Moderation Status</label>
                                                            <select name="status" class="form-select form-select-sm">
                                                                <option value="approved" <?= ($rv['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved (Published)</option>
                                                                <option value="pending" <?= ($rv['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending Moderation</option>
                                                                <option value="rejected" <?= ($rv['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6 d-flex align-items-center pt-3">
                                                            <div class="form-check m-0">
                                                                <input class="form-check-input" type="checkbox" name="is_verified_purchase" value="1" id="edit_rv_ver_<?= (int)$rv['id'] ?>" <?= !empty($rv['is_verified_purchase']) ? 'checked' : '' ?>>
                                                                <label class="form-check-label small fw-semibold" for="edit_rv_ver_<?= (int)$rv['id'] ?>">Verified Purchase</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small fw-semibold">Review Title</label>
                                                        <input type="text" name="title" class="form-control form-control-sm" value="<?= e($rv['title'] ?? '') ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Review Comment *</label>
                                                        <textarea name="comment" class="form-control form-control-sm" rows="3" required><?= e($rv['comment']) ?></textarea>
                                                    </div>
                                                    <div class="row g-2 mb-2">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Update Reviewer Photo</label>
                                                            <input type="file" name="avatar_file" class="form-control form-control-sm" accept="image/*">
                                                            <input type="text" name="avatar_url" class="form-control form-control-sm mt-1" value="<?= e($rv['avatar_url'] ?? '') ?>" placeholder="Or image URL">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Update Received Watch Photo</label>
                                                            <input type="file" name="review_image" class="form-control form-control-sm" accept="image/*">
                                                            <input type="text" name="wristshot_url" class="form-control form-control-sm mt-1" value="<?= e(!empty($rvWristshots) ? $rvWristshots[0] : '') ?>" placeholder="Or image URL">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer py-2">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Hash navigation for section manager tabs
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash;
    if (hash) {
        const triggerEl = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (triggerEl) {
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }
    }
});
</script>
