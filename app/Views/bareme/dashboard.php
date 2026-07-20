<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Barèmes de Frais</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --gradient-start: #3b82f6;
            --gradient-end: #8b5cf6;
            --input-bg: rgba(15, 23, 42, 0.6);
            --input-border: rgba(255, 255, 255, 0.1);
        }

        body {
            margin: 0; padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at top right, rgba(59,130,246,0.15), transparent 40%),
                              radial-gradient(circle at bottom left, rgba(139,92,246,0.15), transparent 40%);
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
        .navbar h1 { margin: 0; font-size: 1.5rem; font-weight: 700; background: linear-gradient(to right, var(--gradient-start), var(--gradient-end)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .navbar a { color: var(--text-secondary); text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar a:hover, .navbar a.active { color: var(--text-primary); }

        .container {
            flex: 1; padding: 3rem;
            max-width: 1300px;
            margin: 0 auto;
            width: 100%; box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
        }
        .header h2 { font-size: 2.5rem; font-weight: 700; margin: 0 0 0.5rem 0; }
        .header p { color: var(--text-secondary); font-size: 1.1rem; margin: 0; }

        .filter-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-bar label { color: var(--text-secondary); font-weight: 500; font-size: 0.95rem; }
        .filter-bar select {
            padding: 0.6rem 2rem 0.6rem 0.9rem;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.95rem;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 0.65rem;
            cursor: pointer;
        }
        .filter-bar select option { background: var(--bg-color); }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th {
            background: rgba(255,255,255,0.03);
            color: var(--text-secondary);
            font-weight: 600;
            padding: 1.2rem 1.5rem;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        td {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 1rem;
            font-weight: 500;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .montant-cell {
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            color: #60a5fa;
        }

        .frais-cell {
            font-weight: 700;
            font-size: 1.05rem;
            color: #fbbf24;
        }

        .type-badge {
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-actif { background: rgba(16,185,129,0.2); color: #34d399; }
        .badge-inactif { background: rgba(239,68,68,0.2); color: #f87171; }

        .btn {
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.3s, transform 0.2s;
            display: inline-block;
        }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }
        .btn-sm { padding: 0.4rem 0.9rem; font-size: 0.85rem; }
        .btn-secondary { background: rgba(255,255,255,0.05); color: var(--text-primary); border: 1px solid rgba(255,255,255,0.1); }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }
        .btn-danger { background: var(--danger); }
        .btn-danger:hover { background: var(--danger-hover); }

        .actions { display: flex; gap: 0.75rem; align-items: center; }

        .empty-state { text-align: center; color: var(--text-secondary); padding: 4rem 2rem; }
        .empty-state p { font-size: 1.1rem; }

        .type-section { margin-bottom: 3rem; }
        .type-section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .type-section-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .type-section-header span {
            background: rgba(59,130,246,0.15);
            color: #60a5fa;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .container { padding: 1.5rem; }
            .header { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
</head>
<body>
    <?= view('operateur/_header') ?>
    <nav class="navbar">
        <h1>MobileMoney</h1>
        <div style="display: flex; gap: 2rem; align-items: center;">
            <a href="<?= site_url('operateur') ?>">Dashboard</a>
            <a href="<?= site_url('prefixe') ?>">Préfixes</a>
            <a href="<?= site_url('typesoperation') ?>">Opérations</a>
            <a href="<?= site_url('bareme') ?>" class="active">Barèmes</a>
            <div style="font-weight: 600; color: var(--text-secondary);">ESPACE OPÉRATEUR</div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <div>
                <h2>Barèmes de Frais</h2>
                <p>Gestion des tranches de frais par type d'opération.</p>
            </div>
            <a href="<?= site_url('bareme/create') ?>" class="btn">+ Ajouter un barème</a>
        </div>

        <?php
        // Regrouper les barèmes par type d'opération
        $baremesByType = [];
        foreach ($baremes as $bareme) {
            $baremesByType[$bareme['type_operation_id']][] = $bareme;
        }

        // Créer un index des types d'opérations
        $typesIndex = [];
        foreach ($typesOperations as $type) {
            $typesIndex[$type['id']] = $type;
        }
        ?>

        <?php if (!empty($baremesByType)): ?>
            <?php foreach ($baremesByType as $typeId => $tranches): ?>
                <?php $typeInfo = $typesIndex[$typeId] ?? null; ?>
                <div class="type-section">
                    <div class="type-section-header">
                        <h3><?= $typeInfo ? esc($typeInfo['libelle']) : 'Type #' . esc($typeId) ?></h3>
                        <span><?= $typeInfo ? esc($typeInfo['code']) : '' ?></span>
                        <span style="background: rgba(139,92,246,0.15); color: #a78bfa;"><?= count($tranches) ?> tranche(s)</span>
                    </div>
                    <div class="card">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Montant min (Ar)</th>
                                    <th>Montant max (Ar)</th>
                                    <th>Frais (Ar)</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tranches as $bareme): ?>
                                    <tr>
                                        <td style="color: var(--text-secondary);">#<?= esc($bareme['id']) ?></td>
                                        <td class="montant-cell"><?= number_format($bareme['montant_min'], 0, ',', ' ') ?></td>
                                        <td class="montant-cell"><?= number_format($bareme['montant_max'], 0, ',', ' ') ?></td>
                                        <td class="frais-cell"><?= number_format($bareme['frais'], 0, ',', ' ') ?></td>
                                        <td>
                                            <?php if (isset($bareme['actif']) && $bareme['actif'] == 1): ?>
                                                <span class="type-badge badge-actif">Actif</span>
                                            <?php else: ?>
                                                <span class="type-badge badge-inactif">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <a href="<?= site_url('bareme/edit/' . esc($bareme['id'])) ?>" class="btn btn-sm btn-secondary">Modifier</a>
                                                <form action="<?= site_url('bareme/delete') ?>" method="post" onsubmit="return confirm('Supprimer cette tranche de barème ?');">
                                                    <input type="hidden" name="id" value="<?= esc($bareme['id']) ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card">
                <div class="empty-state">
                    <p>Aucun barème configuré. Cliquez sur <strong>"+ Ajouter un barème"</strong> pour commencer.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
