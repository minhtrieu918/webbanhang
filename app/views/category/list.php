<?php $pageTitle = 'Danh mục sản phẩm'; require __DIR__ . '/../shares/header.php'; ?>
<div class="d-flex align-items-center justify-content-between gap-3 mb-4"><h1 class="h2 mb-0">Danh mục sản phẩm</h1><a class="btn btn-primary" href="<?= e(url('Category/add')) ?>">Thêm danh mục</a></div>
<div class="card border-0 shadow-sm overflow-hidden"><div class="table-responsive"><table class="table align-middle mb-0">
    <thead class="table-light"><tr><th>#</th><th>Tên danh mục</th><th>Mô tả</th><th>Số sản phẩm</th><th class="text-end">Thao tác</th></tr></thead>
    <tbody>
    <?php foreach ($categories as $category): ?><tr>
        <td><?= (int) $category->id ?></td><td class="fw-semibold"><?= e($category->name) ?></td><td><?= e($category->description) ?></td><td><?= (int) $category->product_count ?></td>
        <td class="text-end"><div class="d-inline-flex gap-2"><a class="btn btn-sm btn-outline-warning" href="<?= e(url('Category/edit/' . $category->id)) ?>">Sửa</a><form method="post" action="<?= e(url('Category/delete/' . $category->id)) ?>" onsubmit="return confirm('Xóa danh mục này?')"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger">Xóa</button></form></div></td>
    </tr><?php endforeach; ?>
    <?php if ($categories === []): ?><tr><td colspan="5" class="text-center text-secondary py-4">Chưa có danh mục.</td></tr><?php endif; ?>
    </tbody>
</table></div></div>
<?php require __DIR__ . '/../shares/footer.php'; ?>

