<?php $pageTitle = 'Sửa danh mục'; require __DIR__ . '/../shares/header.php'; ?>
<div class="form-shell mx-auto"><h1 class="h2 mb-4">Sửa danh mục</h1><form method="post" action="<?= e(url('Category/update')) ?>" class="card border-0 shadow-sm p-4"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $category->id ?>"><?php require __DIR__ . '/_form.php'; ?><div class="d-flex gap-2"><button class="btn btn-primary">Lưu thay đổi</button><a class="btn btn-outline-secondary" href="<?= e(url('Category')) ?>">Hủy</a></div></form></div>
<?php require __DIR__ . '/../shares/footer.php'; ?>

