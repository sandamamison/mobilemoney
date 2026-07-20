<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Préfixes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --gradient-start: #3b82f6;
            --gradient-end: #8b5cf6;
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
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .navbar a:hover {
            color: var(--text-primary);
        }

        .container {
            flex: 1;
            padding: 3rem;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 3rem;
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
            transition: opacity 0.3s ease, transform 0.2s ease;
            display: inline-block;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
        }

        .btn-danger:hover {
            background: var(--danger-hover);
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            font-weight: 600;
            padding: 1.5rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        td {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 500;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .badge {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge.inactive {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .actions {
            display: flex;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }
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
            <div style="font-weight: 600; color: var(--text-secondary);">ESPACE OPÉRATEUR</div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <div>
                <h2>Gestion des Préfixes</h2>
                <p>Liste des numéros de téléphone autorisés sur la plateforme.</p>
            </div>
            <a href="<?= site_url('prefixe/create') ?>" class="btn">+ Ajouter un préfixe</a>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Préfixe</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($prefixe) && is_array($prefixe)): ?>
                        <?php foreach ($prefixe as $item): ?>
                            <tr>
                                <td style="color: var(--text-secondary);">#<?= esc($item['id']) ?></td>
                                <td style="font-size: 1.25rem; letter-spacing: 2px;"><?= esc($item['prefixe']) ?></td>
                                <td>
                                    <?php if (isset($item['actif']) && $item['actif'] == 1): ?>
                                        <span class="badge">Actif</span>
                                    <?php else: ?>
                                        <span class="badge inactive">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <form action="<?= site_url('prefixe/delete') ?>" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer ce préfixe ?');">
                                            <input type="hidden" name="id" value="<?= esc($item['id']) ?>">
                                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 3rem;">
                                Aucun préfixe trouvé. Cliquez sur "Ajouter un préfixe" pour commencer.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
