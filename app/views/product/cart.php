<?php $pageTitle = 'Giỏ hàng'; require __DIR__ . '/../shares/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 mb-0">Giỏ hàng</h1>
    <a href="<?= e(url('Product')) ?>" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
</div>

<?php if ($items === []): ?>
    <div class="alert alert-info">Giỏ hàng đang trống.</div>
<?php else: ?>
    <?php $total = array_sum(array_map(fn ($item) => $item->subtotal, $items)); ?>
    <form method="post" action="<?= e(url('Product/updateCart')) ?>">
        <?= csrf_field() ?>
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Sản phẩm</th><th class="text-end">Đơn giá</th><th style="width:130px">Số lượng</th><th class="text-end">Thành tiền</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><div class="d-flex align-items-center gap-3">
                                <?php if ($item->image): ?><img class="cart-image" src="<?= e(asset($item->image)) ?>" alt=""><?php else: ?><div class="cart-image"></div><?php endif; ?>
                                <div><a class="fw-semibold text-decoration-none text-dark" href="<?= e(url('Product/show/' . $item->id)) ?>"><?= e($item->name) ?></a><div class="small text-secondary"><?= e($item->category_name ?? '') ?></div></div>
                            </div></td>
                            <td class="text-end"><?= number_format((float) $item->price, 0, ',', '.') ?> VND</td>
                            <td><input class="form-control" type="number" name="quantity[<?= (int) $item->id ?>]" min="0" max="99" value="<?= (int) $item->quantity ?>"></td>
                            <td class="text-end fw-semibold"><?= number_format((float) $item->subtotal, 0, ',', '.') ?> VND</td>
                            <td class="text-end">
                                <button form="remove-<?= (int) $item->id ?>" class="btn btn-sm btn-outline-danger">Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot><tr><th colspan="3" class="text-end">Tổng cộng</th><th class="text-end text-danger fs-5"><?= number_format((float) $total, 0, ',', '.') ?> VND</th><th></th></tr></tfoot>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-3"><button class="btn btn-outline-primary">Cập nhật giỏ hàng</button><a class="btn btn-success" href="<?= e(url('Product/checkout')) ?>">Tiến hành đặt hàng</a></div>
    </form>
    <?php foreach ($items as $item): ?>
        <form id="remove-<?= (int) $item->id ?>" method="post" action="<?= e(url('Product/removeFromCart/' . $item->id)) ?>"><?= csrf_field() ?></form>
    <?php endforeach; ?>
<?php endif; ?>
<?php require __DIR__ . '/../shares/footer.php'; ?>

