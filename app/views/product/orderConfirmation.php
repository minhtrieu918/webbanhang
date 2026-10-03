<?php $pageTitle = 'Đặt hàng thành công'; require __DIR__ . '/../shares/header.php'; ?>
<div class="card border-0 shadow-sm p-5 text-center mx-auto" style="max-width:680px">
    <div class="display-3 text-success mb-3">✓</div>
    <h1 class="h2">Đặt hàng thành công</h1>
    <p class="text-secondary">Mã đơn hàng: <strong>#<?= (int) $orderId ?></strong>. Thông tin đơn hàng đã được lưu vào hệ thống.</p>
    <div><a href="<?= e(url('Product')) ?>" class="btn btn-primary">Tiếp tục mua sắm</a></div>
</div>
<?php require __DIR__ . '/../shares/footer.php'; ?>

