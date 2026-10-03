<?php $flashMessages = pull_flashes(); ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Web bán hàng') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('public/css/app.css')) ?>" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= e(url('Product')) ?>">HUTECH Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= e(url('Product')) ?>">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('Category')) ?>">Danh mục</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= e(url('Product/cart')) ?>">
                        Giỏ hàng <span class="badge text-bg-warning"><?= cart_count() ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?php foreach ($flashMessages as $message): ?>
        <div class="alert alert-<?= e($message['type']) ?> alert-dismissible fade show" role="alert">
            <?= e($message['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>

