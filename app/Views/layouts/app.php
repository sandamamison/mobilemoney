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
    <title><?= isset($title) ? $title . ' | Mobile Money' : 'Mobile Money' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/mobilemoney.css?v=1') ?>">
    
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #2563eb;
            --success-color: #059669;
            --danger-color: #dc2626;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body.client-authenticated {
            background: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .card.balance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #047857;
            border-color: #047857;
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }

        .navbar {
            background-color: rgba(30, 64, 175, 0.95) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .container-client {
            margin-top: 2rem;
        }

        .balance-display {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .operation-card {
            transition: transform 0.2s;
        }

        .operation-card:hover {
            transform: translateY(-5px);
        }
    </style>
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
