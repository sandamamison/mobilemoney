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
        .operator-nav { display: flex; align-items: center; justify-content: space-between; color: #fff; }
        .operator-brand { display: flex; align-items: center; gap: 10px; font-size: 1.15rem; font-weight: 800; }
        .operator-mark { width: 36px; height: 36px; display: grid; place-items: center; color: #0f172a; background: #f59e0b; border-radius: 11px; }
        .operator-links { display: flex; gap: 18px; flex-wrap: wrap; }
        .operator-links a { color: #cbd5e1; text-decoration: none; }
        .operator-links a:hover { color: #f59e0b; }
        
        .grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; margin-bottom: 2rem; }
        .metric-card { padding: 24px !important; border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,.1); border-radius: 16px; }
        .metric-icon { width: 42px; height: 42px; display: grid; place-items: center; margin-bottom: 16px; border-radius: 12px; font-size: 1.2rem; }
        
        /* Specific colors for icons */
        .icon-blue { color: #0284c7; background: #e0f2fe; }
        .icon-green { color: #059669; background: #d1fae5; }
        .icon-purple { color: #7c3aed; background: #ede9fe; }
        .icon-orange { color: #ea580c; background: #ffedd5; }
        .icon-red { color: #dc2626; background: #fee2e2; }
        
        .metric-label { color: #64748b; font-size: .76rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .metric-value { margin-top: 7px; font-size: 1.8rem; font-weight: 800; letter-spacing: -.04em; }
        .metric-value small { font-size: 1rem; color: #94a3b8; }
        
        /* Special cards */
        .gain-card { grid-column: span 2; color: #fff !important; background: linear-gradient(135deg, #0f172a, #0f766e) !important; }
        .gain-card .metric-label { color: #ccfbf1; }
        .gain-card .metric-icon { color: #0f172a; background: #34d399; }
        .gain-card .metric-value small { color: rgba(255,255,255,0.7); }

        .reglement-card { grid-column: span 2; color: #fff !important; background: linear-gradient(135deg, #450a0a, #991b1b) !important; }
        .reglement-card .metric-label { color: #fecaca; }
        .reglement-card .metric-icon { color: #450a0a; background: #f87171; }
        .reglement-card .metric-value small { color: rgba(255,255,255,0.7); }
        
        .section-title { font-size: 1.1rem; font-weight: 700; color: #334155; margin-bottom: 1rem; margin-top: 2rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; }

        @media(max-width: 900px){ .grid { grid-template-columns: repeat(2, 1fr); } .gain-card, .reglement-card { grid-column: span 2; } }
        @media(max-width: 540px){ .grid { grid-template-columns: 1fr; } .gain-card, .reglement-card { grid-column: auto; } }
    </style>
</head>
<body style="background-color: #f8fafc;">
    <?= view('operateur/_header') ?>
    
    <nav class="navbar operator-nav" style="background: #1e293b; padding: 1rem 2rem; border-bottom: 1px solid #334155;">
        <div class="operator-brand"><span class="operator-mark"><i class="fa-solid fa-chart-line"></i></span>MobiCash Opérateur</div>
        <div class="operator-links">
            <a href="<?= site_url('operateur') ?>"><i class="fa-solid fa-home"></i> Dashboard</a>
            <a href="<?= site_url('gains') ?>"><i class="fa-solid fa-chart-column"></i> Gains</a>
            <a href="<?= site_url('reglements') ?>"><i class="fa-solid fa-paper-plane"></i> Règlements</a>
            <a href="<?= site_url('autres-operateurs') ?>"><i class="fa-solid fa-tower-broadcast"></i> Autres opérateurs</a>
            <a href="<?= site_url('gains/historique') ?>"><i class="fa-solid fa-clock-rotate-left"></i> Historique</a>
        </div>
    </nav>

    <main class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <header class="header mb-4">
            <div style="color: #0f766e; font-size: .75rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;">Pilotage de la plateforme</div>
            <h1 style="font-weight: 800; color: #0f172a;">Tableau de bord opérateur</h1>
            <p style="color: #64748b;">Les indicateurs essentiels de votre activité Mobile Money.</p>
        </header>

        <h2 class="section-title">Activité Générale</h2>
        <section class="grid">
            <article class="card metric-card">
                <span class="metric-icon icon-blue"><i class="fa-solid fa-users"></i></span>
                <div class="metric-label">Clients</div>
                <div class="metric-value"><?= number_format($nombreDeClients ?? 0, 0, ',', ' ') ?></div>
            </article>
            <article class="card metric-card">
                <span class="metric-icon icon-green"><i class="fa-solid fa-wallet"></i></span>
                <div class="metric-label">Comptes</div>
                <div class="metric-value"><?= number_format($nombreDeComptes ?? 0, 0, ',', ' ') ?></div>
            </article>
            <article class="card metric-card">
                <span class="metric-icon icon-purple"><i class="fa-solid fa-arrow-right-arrow-left"></i></span>
                <div class="metric-label">Opérations</div>
                <div class="metric-value"><?= number_format($nombreDOperations ?? 0, 0, ',', ' ') ?></div>
            </article>
            <article class="card metric-card">
                <span class="metric-icon icon-orange"><i class="fa-solid fa-coins"></i></span>
                <div class="metric-label">Soldes cumulés</div>
                <div class="metric-value"><?= number_format($totalDesSoldes ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
            </article>
        </section>

        <h2 class="section-title">Performances Financières</h2>
        <section class="grid">
            <!-- Gains internes -->
            <article class="card metric-card">
                <span class="metric-icon icon-blue"><i class="fa-solid fa-arrow-down"></i></span>
                <div class="metric-label">Gains Internes</div>
                <div class="metric-value" style="color: #0284c7;"><?= number_format($totalGainsInternes ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
            </article>

            <!-- Gains externes nets -->
            <article class="card metric-card">
                <span class="metric-icon icon-purple"><i class="fa-solid fa-tower-broadcast"></i></span>
                <div class="metric-label">Gains Ext. (Net)</div>
                <div class="metric-value" style="color: #7c3aed;"><?= number_format($gainNetExternes ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
            </article>

            <!-- Gain total net -->
            <article class="card metric-card gain-card">
                <span class="metric-icon"><i class="fa-solid fa-chart-line"></i></span>
                <div class="metric-label">Revenus Nets (Frais)</div>
                <div class="metric-value"><?= number_format($totalGainNet ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
            </article>
        </section>

        <h2 class="section-title">Opérateurs & Règlements</h2>
        <section class="grid">
            <!-- Nombre d'opérateurs externes -->
            <article class="card metric-card" style="grid-column: span 2;">
                <span class="metric-icon icon-orange"><i class="fa-solid fa-network-wired"></i></span>
                <div class="metric-label">Opérateurs Externes</div>
                <div class="metric-value"><?= number_format($nombreOperateursExternes ?? 0, 0, ',', ' ') ?> <small>partenaires</small></div>
            </article>

            <!-- Montants à régler -->
            <article class="card metric-card reglement-card">
                <span class="metric-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                <div class="metric-label">Montants à régler</div>
                <div class="metric-value"><?= number_format($totalARegler ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
            </article>
        </section>
    </main>
</body>
</html>
