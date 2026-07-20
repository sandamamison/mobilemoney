<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail du Compte</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a; --card: rgba(30,41,59,0.7); --text: #f8fafc;
            --muted: #94a3b8; --g1: #3b82f6; --g2: #8b5cf6;
            --border: rgba(255,255,255,0.07);
        }
        body { margin:0; font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; display:flex; flex-direction:column; }
        body { background-image: radial-gradient(circle at top right, rgba(59,130,246,0.12), transparent 40%), radial-gradient(circle at bottom left, rgba(139,92,246,0.12), transparent 40%); }
        .navbar { padding:1.5rem 3rem; background:rgba(15,23,42,0.85); backdrop-filter:blur(12px); border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
        .navbar h1 { margin:0; font-size:1.5rem; font-weight:700; background:linear-gradient(to right,var(--g1),var(--g2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .navbar a { color:var(--muted); text-decoration:none; font-weight:500; transition:color 0.3s; }
        .navbar a:hover { color:var(--text); }
        .container { flex:1; padding:3rem; max-width:1100px; margin:0 auto; width:100%; box-sizing:border-box; }

        .back { color:var(--muted); text-decoration:none; font-size:0.95rem; display:inline-flex; align-items:center; gap:0.4rem; margin-bottom:2rem; transition:color 0.3s; }
        .back:hover { color:var(--text); }

        .info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.5rem; margin-bottom:3rem; }
        .info-card { background:var(--card); backdrop-filter:blur(16px); border:1px solid var(--border); border-radius:14px; padding:1.5rem; position:relative; overflow:hidden; transition:transform 0.3s; }
        .info-card:hover { transform:translateY(-4px); }
        .info-card::before { content:''; position:absolute; top:0; left:0; width:100%; height:3px; background:linear-gradient(90deg,var(--g1),var(--g2)); }
        .info-card .label { color:var(--muted); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-bottom:0.6rem; }
        .info-card .value { font-size:1.6rem; font-weight:700; }
        .info-card .value.solde { color:#fbbf24; }
        .info-card .value.actif  { color:#34d399; }
        .info-card .value.bloque { color:#f87171; }

        h3 { font-size:1.4rem; font-weight:700; margin:0 0 1.25rem 0; }
        .card { background:var(--card); backdrop-filter:blur(16px); border:1px solid var(--border); border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2); margin-bottom:2rem; }
        table { width:100%; border-collapse:collapse; text-align:left; }
        th { background:rgba(255,255,255,0.03); color:var(--muted); font-weight:600; padding:1.2rem 1.5rem; text-transform:uppercase; font-size:0.8rem; letter-spacing:1px; border-bottom:1px solid var(--border); }
        td { padding:1.1rem 1.5rem; border-bottom:1px solid var(--border); font-size:0.98rem; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(255,255,255,0.02); }

        .badge { padding:0.3rem 0.75rem; border-radius:20px; font-size:0.82rem; font-weight:600; }
        .badge-validee  { background:rgba(52,211,153,0.15); color:#34d399; }
        .badge-annulee  { background:rgba(248,113,113,0.15); color:#f87171; }
        .badge-echec    { background:rgba(245,158,11,0.15); color:#fbbf24; }

        .empty-state { text-align:center; color:var(--muted); padding:3rem; }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
</head>
<body>
    <?= view('operateur/_header') ?>
    <nav class="navbar">
        <h1>MobileMoney</h1>
        <div style="display:flex;gap:2rem;align-items:center;">
            <a href="<?= site_url('operateur') ?>">Dashboard</a>
            <a href="<?= site_url('comptes') ?>">Comptes</a>
            <div style="font-weight:600;color:var(--muted);">ESPACE OPÉRATEUR</div>
        </div>
    </nav>

    <div class="container">
        <a href="<?= site_url('comptes') ?>" class="back">← Retour à la liste</a>

        <?php if ($compte): ?>

        <!-- Infos du compte -->
        <div class="info-grid">
            <div class="info-card">
                <div class="label">Téléphone</div>
                <div class="value" style="font-size:1.3rem;"><?= esc($compte['telephone']) ?></div>
            </div>
            <div class="info-card">
                <div class="label">Nom</div>
                <div class="value" style="font-size:1.3rem;"><?= esc($compte['nom'] ?? '—') ?></div>
            </div>
            <div class="info-card">
                <div class="label">Solde</div>
                <div class="value solde"><?= number_format($compte['solde'], 0, ',', ' ') ?> <span style="font-size:1rem;font-weight:400;">Ar</span></div>
            </div>
            <div class="info-card">
                <div class="label">Statut</div>
                <?php if ($compte['statut'] === 'ACTIF'): ?>
                    <div class="value actif">Actif</div>
                <?php else: ?>
                    <div class="value bloque">Bloqué</div>
                <?php endif; ?>
            </div>
            <div class="info-card">
                <div class="label">Compte créé le</div>
                <div class="value" style="font-size:1.1rem;"><?= esc(substr($compte['date_creation'], 0, 10)) ?></div>
            </div>
        </div>

        <!-- Historique des opérations -->
        <h3>Historique des opérations</h3>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Type</th>
                        <th>Montant (Ar)</th>
                        <th>Frais (Ar)</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($operations)): ?>
                        <?php foreach ($operations as $op): ?>
                            <tr>
                                <td style="font-family:'Courier New',monospace;font-size:0.9rem;color:var(--muted);"><?= esc($op['reference']) ?></td>
                                <td style="font-weight:600;"><?= esc($op['type_libelle']) ?></td>
                                <td style="font-weight:700;"><?= number_format($op['montant'], 0, ',', ' ') ?></td>
                                <td style="color:#fbbf24;"><?= number_format($op['frais'], 0, ',', ' ') ?></td>
                                <td>
                                    <?php
                                    $statut = strtoupper($op['statut']);
                                    $cls = match($statut) {
                                        'VALIDEE'  => 'badge-validee',
                                        'ANNULEE'  => 'badge-annulee',
                                        default    => 'badge-echec',
                                    };
                                    ?>
                                    <span class="badge <?= $cls ?>"><?= esc($op['statut']) ?></span>
                                </td>
                                <td style="color:var(--muted);font-size:0.9rem;"><?= esc(substr($op['date_operation'], 0, 16)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="empty-state">Aucune opération pour ce compte.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php else: ?>
            <p style="color:var(--muted);">Compte introuvable.</p>
        <?php endif; ?>
    </div>
</body>
</html>
