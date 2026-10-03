<?php $pageTitle = $product->name; require __DIR__ . '/../shares/header.php'; ?>
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="row g-0">
        <div class="col-lg-6">
            <?php if ($product->image): ?><img class="detail-image h-100" src="<?= e(asset($product->image)) ?>" alt="<?= e($product->name) ?>"><?php else: ?><div class="product-placeholder h-100">Chưa có hình ảnh</div><?php endif; ?>
        </div>
        <div class="col-lg-6"><div class="card-body p-4 p-lg-5">
            <span class="badge text-bg-secondary mb-3"><?= e($product->category_name ?? 'Chưa phân loại') ?></span>
            <h1 class="display-6 fw-bold"><?= e($product->name) ?></h1>
            <p class="lead text-secondary"><?= nl2br(e($product->description)) ?></p>
            <p class="display-6 text-danger fw-bold"><?= number_format((float) $product->price, 0, ',', '.') ?> VND</p>
            <div class="d-flex gap-2 mt-4">
                <form method="post" action="<?= e(url('Product/addToCart/' . $product->id)) ?>"><?= csrf_field() ?><button class="btn btn-success btn-lg">Thêm vào giỏ</button></form>
                <a class="btn btn-outline-secondary btn-lg" href="<?= e(url('Product')) ?>">Quay lại</a>
            </div>
        </div></div>
    </div>
</div>
<?php require __DIR__ . '/../shares/footer.php'; ?>

