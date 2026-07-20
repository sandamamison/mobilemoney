<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptes Clients</title>
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
        .navbar a:hover, .navbar a.active { color:var(--text); }
        .container { flex:1; padding:3rem; max-width:1200px; margin:0 auto; width:100%; box-sizing:border-box; }
        .header { display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2.5rem; }
        .header h2 { font-size:2.5rem; font-weight:700; margin:0 0 0.4rem 0; }
        .header p { color:var(--muted); font-size:1.05rem; margin:0; }
        .card { background:var(--card); backdrop-filter:blur(16px); border:1px solid var(--border); border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2); }
        table { width:100%; border-collapse:collapse; text-align:left; }
        th { background:rgba(255,255,255,0.03); color:var(--muted); font-weight:600; padding:1.2rem 1.5rem; text-transform:uppercase; font-size:0.8rem; letter-spacing:1px; border-bottom:1px solid var(--border); }
        td { padding:1.2rem 1.5rem; border-bottom:1px solid var(--border); font-size:1rem; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(255,255,255,0.02); }
        .badge { padding:0.35rem 0.8rem; border-radius:20px; font-size:0.82rem; font-weight:600; }
        .badge-actif   { background:rgba(52,211,153,0.15); color:#34d399; }
        .badge-bloque  { background:rgba(248,113,113,0.15); color:#f87171; }
        .actions { display:flex; gap:0.75rem; }
        .btn { padding:0.5rem 1rem; border-radius:8px; font-size:0.88rem; font-weight:600; cursor:pointer; text-decoration:none; border:none; transition:all 0.25s; display:inline-block; }
        .btn-info    { background:rgba(59,130,246,0.15); color:#60a5fa; }
        .btn-info:hover { background:rgba(59,130,246,0.3); }
        .btn-danger  { background:rgba(239,68,68,0.15); color:#f87171; }
        .btn-danger:hover { background:rgba(239,68,68,0.3); }
        .btn-success { background:rgba(52,211,153,0.15); color:#34d399; }
        .btn-success:hover { background:rgba(52,211,153,0.3); }
        .solde-cell { font-weight:700; color:#fbbf24; font-family:'Courier New',monospace; }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
</head>
<body>
    <?= view('operateur/_header') ?>
    <nav class="navbar">
        <h1>MobileMoney</h1>
        <div style="display:flex;gap:2rem;align-items:center;">
            <a href="<?= site_url('operateur') ?>">Dashboard</a>
            <a href="<?= site_url('prefixe') ?>">Préfixes</a>
            <a href="<?= site_url('typesoperation') ?>">Opérations</a>
            <a href="<?= site_url('bareme') ?>">Barèmes</a>
            <a href="<?= site_url('comptes') ?>" class="active">Comptes</a>
            <div style="font-weight:600;color:var(--muted);">ESPACE OPÉRATEUR</div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <div>
                <h2>Comptes Clients</h2>
                <p>Liste de tous les comptes enregistrés sur la plateforme.</p>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Téléphone</th>
                        <th>Nom</th>
                        <th>Solde (Ar)</th>
                        <th>Statut</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($comptes)): ?>
                        <?php foreach ($comptes as $c): ?>
                            <tr>
                                <td style="color:var(--muted);">#<?= esc($c['id']) ?></td>
                                <td style="font-weight:600;"><?= esc($c['telephone']) ?></td>
                                <td><?= esc($c['nom'] ?? '—') ?></td>
                                <td class="solde-cell"><?= number_format($c['solde'], 0, ',', ' ') ?></td>
                                <td>
                                    <?php if ($c['statut'] === 'ACTIF'): ?>
                                        <span class="badge badge-actif">Actif</span>
                                    <?php else: ?>
                                        <span class="badge badge-bloque">Bloqué</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color:var(--muted);font-size:0.9rem;"><?= esc(substr($c['date_creation'], 0, 10)) ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="<?= site_url('comptes/show/' . $c['id']) ?>" class="btn btn-info">Détail</a>

                                        <?php if ($c['statut'] === 'ACTIF'): ?>
                                            <form action="<?= site_url('comptes/bloque/' . $c['id']) ?>" method="post" onsubmit="return confirm('Bloquer ce compte ?');">
                                                <button type="submit" class="btn btn-danger">Bloquer</button>
                                            </form>
                                        <?php else: ?>
                                            <form action="<?= site_url('comptes/debloque/' . $c['id']) ?>" method="post" onsubmit="return confirm('Débloquer ce compte ?');">
                                                <button type="submit" class="btn btn-success">Débloquer</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--muted);padding:3rem;">Aucun compte trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
