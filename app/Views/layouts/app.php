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
            <div class="client-nav d-flex align-items-center gap-2">
                <a href="<?= base_url('/client/dashboard') ?>" class="client-nav-link"><i class="fa-solid fa-house"></i><span>Accueil</span></a>
                <a href="<?= base_url('/client/transfert') ?>" class="client-nav-link"><i class="fa-solid fa-arrow-right-arrow-left"></i><span>Transfert</span></a>
                <a href="<?= base_url('/client/transfert-multiple') ?>" class="client-nav-link"><i class="fa-solid fa-users"></i><span>Envoi multiple</span></a>
                <a href="<?= base_url('/client/operations') ?>" class="client-nav-link"><i class="fa-solid fa-clock-rotate-left"></i><span>Historique</span></a>
                <a href="<?= base_url('/deconnexion') ?>" class="btn btn-sm btn-outline-light px-3">
                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i><span class="logout-label"> Quitter</span>
                </a>
            </div>
        </div>
    </nav>
<?php endif; ?>

<?= $this->renderSection('content') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
