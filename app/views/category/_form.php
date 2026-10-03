<?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="mb-3"><label class="form-label" for="name">Tên danh mục</label><input class="form-control" id="name" name="name" minlength="2" maxlength="100" value="<?= e($category->name ?? $old['name'] ?? '') ?>" required></div>
<div class="mb-4"><label class="form-label" for="description">Mô tả</label><textarea class="form-control" id="description" name="description" rows="4"><?= e($category->description ?? $old['description'] ?? '') ?></textarea></div>

