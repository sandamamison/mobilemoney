<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord · Opérateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
    <style>
        .operator-nav{display:flex;align-items:center;justify-content:space-between;color:#fff}.operator-brand{display:flex;align-items:center;gap:10px;font-size:1.15rem;font-weight:800}.operator-mark{width:36px;height:36px;display:grid;place-items:center;color:#0f172a;background:#f59e0b;border-radius:11px}.operator-links{display:flex;gap:18px}.operator-links a{color:#cbd5e1}.operator-links a:hover{color:#f59e0b}.grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}.metric-card{padding:24px!important}.metric-icon{width:42px;height:42px;display:grid;place-items:center;margin-bottom:22px;color:#0f766e;background:#ccfbf1;border-radius:12px}.metric-label{color:#64748b;font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.metric-value{margin-top:7px;font-size:2rem;font-weight:800;letter-spacing:-.04em}.gain-card{grid-column:1/-1;color:#fff!important;background:linear-gradient(135deg,#0f172a,#0f766e)!important;border:0!important}.gain-card .metric-label{color:#ccfbf1}.gain-card .metric-icon{color:#0f172a;background:#f59e0b}@media(max-width:900px){.grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:540px){.grid{grid-template-columns:1fr}.gain-card{grid-column:auto}}
    </style>
</head>
<body>
    <?= view('operateur/_header') ?>
    <nav class="navbar operator-nav">
        <div class="operator-brand"><span class="operator-mark"><i class="fa-solid fa-chart-line"></i></span>MobiCash Opérateur</div>
        <div class="operator-links"><a href="<?= site_url('operateur') ?>">Dashboard</a><a href="<?= site_url('gains') ?>">Gains</a><a href="<?= site_url('gains/historique') ?>">Historique</a></div>
    </nav>
    <main class="container">
        <header class="header"><div style="color:#0f766e;font-size:.75rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase">Pilotage de la plateforme</div><h1>Tableau de bord opérateur</h1><p>Les indicateurs essentiels de votre activité Mobile Money.</p></header>
        <section class="grid">
            <article class="card metric-card"><span class="metric-icon"><i class="fa-solid fa-users"></i></span><div class="metric-label">Clients</div><div class="metric-value"><?= number_format($nombreDeClients ?? 0, 0, ',', ' ') ?></div></article>
            <article class="card metric-card"><span class="metric-icon"><i class="fa-solid fa-wallet"></i></span><div class="metric-label">Comptes</div><div class="metric-value"><?= number_format($nombreDeComptes ?? 0, 0, ',', ' ') ?></div></article>
            <article class="card metric-card"><span class="metric-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></span><div class="metric-label">Opérations</div><div class="metric-value"><?= number_format($nombreDOperations ?? 0, 0, ',', ' ') ?></div></article>
            <article class="card metric-card"><span class="metric-icon"><i class="fa-solid fa-coins"></i></span><div class="metric-label">Soldes cumulés</div><div class="metric-value"><?= number_format($totalDesSoldes ?? 0, 0, ',', ' ') ?> <small>Ar</small></div></article>
            <article class="card metric-card gain-card"><span class="metric-icon"><i class="fa-solid fa-chart-line"></i></span><div class="metric-label">Revenus sur les frais</div><div class="metric-value"><?= number_format($totalDesFrais ?? 0, 0, ',', ' ') ?> <small>Ar</small></div></article>
        </section>
    </main>
</body>
</html>
