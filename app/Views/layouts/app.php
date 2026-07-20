<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' | Mobile Money' : 'Mobile Money' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
<body<?= session()->get('is_logged_in') ? ' class="client-authenticated"' : '' ?>>
    <?php if (session()->get('is_logged_in')) : ?>
        <nav class="navbar navbar-dark sticky-top">
            <div class="container-fluid">
                <span class="navbar-brand">
                    <i class="fas fa-mobile-alt"></i> Mobile Money
                </span>
                <div class="text-white">
                    <span><?= session()->get('telephone') ?? '' ?></span>
                    <a href="<?= base_url('/auth/logout') ?>" class="btn btn-outline-light btn-sm ms-3">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
