<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique Global</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --gradient-start: #3b82f6;
            --gradient-end: #8b5cf6;
            --border: rgba(255,255,255,0.08);
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at top right, rgba(59, 130, 246, 0.15), transparent 40%),
                              radial-gradient(circle at bottom left, rgba(139, 92, 246, 0.15), transparent 40%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            padding: 1.5rem 3rem;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            margin-left: 1rem;
        }

        .container {
            flex: 1;
            padding: 3rem;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .header {
            margin-bottom: 2rem;
        }

        .header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin: 0;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .field label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.45rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .field input,
        .field select {
            width: 100%;
            box-sizing: border-box;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(15, 23, 42, 0.7);
            color: var(--text-primary);
            padding: 0.9rem 1rem;
            outline: none;
        }

        .filter-actions {
            display: flex;
            align-items: end;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.95rem 1.25rem;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s ease, background 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: linear-gradient(135deg, var(--accent), var(--gradient-end)); color: #fff; }
        .btn-secondary { background: rgba(148, 163, 184, 0.12); color: var(--text-primary); border: 1px solid rgba(255,255,255,0.08); }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(255,255,255,0.03);
            color: var(--text-secondary);
            font-weight: 600;
            padding: 1.1rem 1.25rem;
            text-transform: uppercase;
            font-size: 0.78rem;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        tr:hover td { background: rgba(255,255,255,0.02); }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .badge-retrait,
        .badge-transfert,
        .badge-depot {
            background: rgba(59, 130, 246, 0.16);
            color: #93c5fd;
        }

        .empty-state {
            padding: 2.5rem 1.5rem;
            text-align: center;
            color: var(--text-secondary);
        }

        .summary {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.5rem 0;
            color: var(--text-secondary);
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .container { padding: 1.5rem; }
            .filters { grid-template-columns: 1fr; }
            table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>MobileMoney</h1>
        <div>
            <a href="<?= site_url('gains') ?>">Gains</a>
            <a href="<?= site_url('operateur') ?>">Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h2>Historique global</h2>
            <p>Affichage des opérations validées avec filtres par date, type et numéro.</p>
        </div>

        <form class="card" method="get" action="<?= site_url('gains/historique') ?>">
            <div class="filters">
                <div class="field">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" value="<?= esc($filtres['date'] ?? '') ?>">
                </div>
                <div class="field">
                    <label for="type">Type</label>
                    <select name="type" id="type">
                        <option value="">Tous les types</option>
                        <?php foreach (($types ?? []) as $type): ?>
                            <option value="<?= esc($type['code']) ?>" <?= (($filtres['type'] ?? '') === $type['code']) ? 'selected' : '' ?>>
                                <?= esc($type['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="numero">Numéro</label>
                    <input type="text" name="numero" id="numero" placeholder="Téléphone ou compte" value="<?= esc($filtres['numero'] ?? '') ?>">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="<?= site_url('gains/historique') ?>" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </div>

            <div class="summary">
                <div><?= count($historique ?? []) ?> opération(s) trouvée(s)</div>
                <div><a href="<?= site_url('gains') ?>" style="color:var(--text-secondary);text-decoration:none;">Voir les gains</a></div>
            </div>

            <div style="padding: 1.25rem 0 0; overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Numéro(s)</th>
                            <th>Montant (Ar)</th>
                            <th>Frais (Ar)</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($historique)): ?>
                            <?php foreach ($historique as $operation): ?>
                                <?php
                                    $code = strtolower((string) ($operation['type_code'] ?? ''));
                                    $badge = in_array($code, ['retrait', 'transfert', 'depot'], true) ? $code : 'depot';
                                ?>
                                <tr>
                                    <td><?= esc(substr($operation['date_operation'], 0, 16)) ?></td>
                                    <td><span class="badge badge-<?= esc($badge) ?>"><?= esc($operation['type_libelle']) ?></span></td>
                                    <td><?= esc($operation['numeros_concernes'] ?? $operation['numero_principal'] ?? '—') ?></td>
                                    <td><?= number_format((int) $operation['montant'], 0, ',', ' ') ?></td>
                                    <td><?= number_format((int) $operation['frais'], 0, ',', ' ') ?></td>
                                    <td style="font-family: 'Courier New', monospace; color: var(--text-secondary);"><?= esc($operation['reference']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-state">Aucune opération ne correspond aux filtres appliqués.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</body>
</html>