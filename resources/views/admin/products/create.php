<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Add New Watch</h3>
        <p class="text-muted small m-0">Create a new watch catalog item with technical specifications and pricing.</p>
    </div>
    <a href="/admin/products" class="btn btn-secondary btn-sm">Cancel</a>
</div>

<div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
    <form id="createProductForm" action="/admin/products/store?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Watch Name *</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Welkar Vanguard Automatic">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Brand *</label>
                <select name="brand_id" class="form-select" required>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= (int)$b['id'] ?>"><?= e($b['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">None / Unassigned</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Base SKU</label>
                <input type="text" name="base_sku" class="form-control" placeholder="Auto-generated if blank">
                <div class="form-text small" style="font-size: 10px;">Optional: e.g. WLK-VNG-01</div>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Selling Price (INR ₹) *</label>
                <input type="number" step="0.01" name="base_price" class="form-control" required placeholder="3999.00">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">MRP / Compare Price (₹)</label>
                <input type="number" step="0.01" name="compare_at_price" class="form-control" placeholder="5499.00">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Cost Price (₹)</label>
                <input type="number" step="0.01" name="cost_price" class="form-control" placeholder="Optional">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Gender</label>
                <select name="gender" class="form-select">
                    <option value="Men">Men</option>
                    <option value="Women">Women</option>
                    <option value="Unisex">Unisex</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Dial Type *</label>
                <select name="dial_type" class="form-select">
                    <option value="Classic Roman">Classic Roman</option>
                    <option value="Minimalist Stick">Minimalist Stick</option>
                    <option value="Chronograph">Chronograph</option>
                    <option value="Automatic">Automatic Skeleton</option>
                    <option value="Analog">Analog</option>
                    <option value="Digital">Digital</option>
                    <option value="Moon Phase">Moon Phase</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Movement</label>
                <input type="text" name="movement" class="form-control" value="Japanese Quartz">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Initial Stock</label>
                <input type="number" name="initial_stock" class="form-control" value="25" min="0">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold">Case Material</label>
                <input type="text" name="case_material" class="form-control" value="316L Stainless Steel">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold">Strap Material</label>
                <input type="text" name="strap_material" class="form-control" value="Stainless Steel Link">
            </div>

            <!-- Product Media & Images Upload -->
            <div class="col-12 border rounded p-3 bg-light">
                <h6 class="fw-bold mb-2"><i class="bi bi-images text-dark me-2"></i>Product Photos & Gallery</h6>
                <p class="text-muted small mb-3">Upload watch photos from your device. You can select multiple images at once (JPG, PNG, WEBP, GIF). The first image will be set as the primary display photo.</p>

                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label small fw-bold">Upload Photos from Device</label>
                        <input type="file" name="images[]" id="productImageUpload" class="form-control" multiple accept="image/*">
                        <div class="form-text small">Hold Ctrl or Shift to select multiple photos. Max 10MB per image.</div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Or External Image URL (Optional)</label>
                        <input type="text" name="image_url" class="form-control" placeholder="https://example.com/watch.jpg">
                        <div class="form-text small">Fallback if you prefer linking an existing hosted URL.</div>
                    </div>
                </div>

                <!-- Live Preview Area -->
                <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mt-3" style="display: none !important;"></div>
            </div>

            <div class="col-12">
                <label class="form-label small fw-bold">Short Description</label>
                <textarea name="short_description" class="form-control" rows="2" placeholder="Brief summary of watch features..."></textarea>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-dark fw-bold px-4">
                    <i class="bi bi-check2-circle me-1"></i> Create Watch & Save Photos
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('productImageUpload');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const form = document.getElementById('createProductForm');
    const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

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
                        wrapper.style.width = '95px';
                        wrapper.style.height = '95px';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-100 h-100 object-fit-cover rounded';

                        const badge = document.createElement('span');
                        badge.className = 'position-absolute top-0 start-0 badge ' + (idx === 0 ? 'bg-primary' : 'bg-dark');
                        badge.style.fontSize = '9px';
                        badge.innerText = (idx === 0 ? 'Primary' : '#' + (idx + 1)) + ' (' + formatBytes(file.size) + ')';

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

    if (form && submitBtn) {
        form.addEventListener('submit', async function(e) {
            if (fileInput && fileInput.files && fileInput.files.length > 0) {
                const rawFiles = Array.from(fileInput.files);
                const hasOversized = rawFiles.some(f => f.size > 1.2 * 1024 * 1024);
                if (hasOversized) {
                    e.preventDefault();
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Optimizing high-res photos...';
                    try {
                        const compressedFiles = await Promise.all(rawFiles.map(f => compressImage(f)));
                        const dt = new DataTransfer();
                        compressedFiles.forEach(f => dt.items.add(f));
                        fileInput.files = dt.files;
                    } catch (err) {
                        console.error('Compression error:', err);
                    }
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving watch...';
                    form.submit();
                    return;
                }
            }
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating watch...';
        });
    }
});
</script>
