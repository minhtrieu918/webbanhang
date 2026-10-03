<?php $pageTitle = 'Danh sách sản phẩm'; require __DIR__ . '/../shares/header.php'; ?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1">Danh sách sản phẩm</h1>
        <p class="text-secondary mb-0">Quản lý và chọn sản phẩm cho đơn hàng.</p>
    </div>
    <a href="<?= e(url('Product/add')) ?>" class="btn btn-primary">Thêm sản phẩm</a>
</div>

<?php if ($products === []): ?>
    <div class="alert alert-info">Chưa có sản phẩm. Hãy thêm sản phẩm đầu tiên.</div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($products as $product): ?>
            <div class="col-sm-6 col-lg-4">
                <article class="card product-card h-100 border-0 shadow-sm overflow-hidden">
                    <?php if ($product->image): ?>
                        <img class="product-image" src="<?= e(asset($product->image)) ?>" alt="<?= e($product->name) ?>">
                    <?php else: ?>
                        <div class="product-placeholder">Chưa có hình ảnh</div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <span class="badge text-bg-light align-self-start mb-2"><?= e($product->category_name ?? 'Chưa phân loại') ?></span>
                        <h2 class="h5"><a class="text-decoration-none text-dark" href="<?= e(url('Product/show/' . $product->id)) ?>"><?= e($product->name) ?></a></h2>
                        <p class="text-secondary flex-grow-1"><?= e($product->description) ?></p>
                        <p class="fs-5 fw-bold text-danger mb-3"><?= number_format((float) $product->price, 0, ',', '.') ?> VND</p>
                        <div class="d-flex flex-wrap gap-2">
                            <form method="post" action="<?= e(url('Product/addToCart/' . $product->id)) ?>">
                                <?= csrf_field() ?>
                                <button class="btn btn-success btn-sm">Thêm vào giỏ</button>
                            </form>
                            <a href="<?= e(url('Product/edit/' . $product->id)) ?>" class="btn btn-outline-warning btn-sm">Sửa</a>
                            <form method="post" action="<?= e(url('Product/delete/' . $product->id)) ?>" onsubmit="return confirm('Xóa sản phẩm này?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-outline-danger btn-sm">Xóa</button>
                            </form>
                        </div>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../shares/footer.php'; ?>

