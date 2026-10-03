<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="mb-3">
    <label for="name" class="form-label">Tên sản phẩm</label>
    <input type="text" id="name" name="name" class="form-control" minlength="10" maxlength="100"
           value="<?= e($product->name ?? $old['name'] ?? '') ?>" required>
    <div class="form-text">Từ 10 đến 100 ký tự.</div>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Mô tả</label>
    <textarea id="description" name="description" class="form-control" rows="5" required><?= e($product->description ?? $old['description'] ?? '') ?></textarea>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="price" class="form-label">Giá</label>
        <div class="input-group"><input type="number" id="price" name="price" class="form-control" min="0.01" step="0.01" value="<?= e($product->price ?? $old['price'] ?? '') ?>" required><span class="input-group-text">VND</span></div>
    </div>
    <div class="col-md-6">
        <label for="category_id" class="form-label">Danh mục</label>
        <select id="category_id" name="category_id" class="form-select" required>
            <option value="">-- Chọn danh mục --</option>
            <?php $selectedCategory = (string) ($product->category_id ?? $old['category_id'] ?? ''); ?>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category->id ?>" <?= (string) $category->id === $selectedCategory ? 'selected' : '' ?>><?= e($category->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="mb-4">
    <label for="image" class="form-label">Hình ảnh</label>
    <input type="file" id="image" name="image" class="form-control" accept="image/jpeg,image/png,image/gif">
    <div class="form-text">JPG, PNG hoặc GIF; tối đa 10 MB.</div>
    <?php if (!empty($product->image)): ?><img class="mt-3 rounded" src="<?= e(asset($product->image)) ?>" alt="Ảnh hiện tại" style="max-width: 180px; max-height: 140px; object-fit: cover"><?php endif; ?>
</div>

