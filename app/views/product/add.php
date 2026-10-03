<?php $pageTitle = 'Thêm sản phẩm'; require __DIR__ . '/../shares/header.php'; ?>
<div class="form-shell mx-auto">
    <h1 class="h2 mb-4">Thêm sản phẩm</h1>
    <form method="post" action="<?= e(url('Product/save')) ?>" enctype="multipart/form-data" class="card border-0 shadow-sm p-4">
        <?= csrf_field() ?>
        <?php require __DIR__ . '/_form.php'; ?>
        <div class="d-flex gap-2"><button class="btn btn-primary">Lưu sản phẩm</button><a class="btn btn-outline-secondary" href="<?= e(url('Product')) ?>">Hủy</a></div>
    </form>
</div>
<?php require __DIR__ . '/../shares/footer.php'; ?>

