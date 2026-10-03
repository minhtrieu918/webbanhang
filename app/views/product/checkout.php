<?php $pageTitle = 'Đặt hàng'; require __DIR__ . '/../shares/header.php'; ?>
<?php $total = array_sum(array_map(fn ($item) => $item->subtotal, $items)); ?>
<h1 class="h2 mb-4">Thông tin đặt hàng</h1>
<div class="row g-4">
    <div class="col-lg-7">
        <form method="post" action="<?= e(url('Product/processCheckout')) ?>" class="card border-0 shadow-sm p-4">
            <?= csrf_field() ?>
            <?php if ($errors !== []): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
            <div class="mb-3"><label class="form-label" for="name">Họ và tên</label><input class="form-control" id="name" name="name" maxlength="100" value="<?= e($old['name'] ?? '') ?>" required></div>
            <div class="mb-3"><label class="form-label" for="phone">Số điện thoại</label><input class="form-control" id="phone" name="phone" maxlength="20" value="<?= e($old['phone'] ?? '') ?>" required></div>
            <div class="mb-4"><label class="form-label" for="address">Địa chỉ nhận hàng</label><textarea class="form-control" id="address" name="address" rows="3" maxlength="255" required><?= e($old['address'] ?? '') ?></textarea></div>
            <div class="d-flex gap-2"><button class="btn btn-success">Xác nhận đặt hàng</button><a class="btn btn-outline-secondary" href="<?= e(url('Product/cart')) ?>">Quay lại giỏ hàng</a></div>
        </form>
    </div>
    <div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Đơn hàng</div><ul class="list-group list-group-flush">
        <?php foreach ($items as $item): ?><li class="list-group-item d-flex justify-content-between gap-3"><span><?= e($item->name) ?> x <?= (int) $item->quantity ?></span><strong><?= number_format((float) $item->subtotal, 0, ',', '.') ?> VND</strong></li><?php endforeach; ?>
        <li class="list-group-item d-flex justify-content-between fs-5"><strong>Tổng cộng</strong><strong class="text-danger"><?= number_format((float) $total, 0, ',', '.') ?> VND</strong></li>
    </ul></div></div>
</div>
<?php require __DIR__ . '/../shares/footer.php'; ?>

