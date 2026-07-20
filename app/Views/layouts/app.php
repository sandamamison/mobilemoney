<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title><?= isset($title) ? esc($title) . ' · MobiCash' : 'MobiCash' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/mobilemoney.css') ?>">
</head>
<body>
<?php if (session()->get('is_logged_in')): ?>
    <nav class="navbar app-navbar sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('/client/dashboard') ?>">
                <span class="brand-mark"><i class="fa-solid fa-wallet"></i></span>
                <span>MobiCash</span>
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= base_url('/client/dashboard') ?>" class="nav-phone"><i class="fa-solid fa-house me-2"></i>Tableau de bord</a>
                <span class="nav-phone"><i class="fa-solid fa-phone me-2"></i><?= esc(session()->get('telephone')) ?></span>
                <a href="<?= base_url('/deconnexion') ?>" class="btn btn-sm btn-outline-light px-3">
                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Quitter
                </a>
            </div>
        </div>
    </nav>
<?php endif; ?>

<?= $this->renderSection('content') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
