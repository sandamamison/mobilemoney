<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gains Opérateur</title>
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
        }

        .container {
            flex: 1;
            padding: 3rem;
            max-width: 1200px;
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

        .actions {
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            padding: 0.9rem 1.25rem;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: linear-gradient(135deg, var(--accent), var(--gradient-end)); color: #fff; }
        .btn-secondary { background: rgba(148, 163, 184, 0.12); color: var(--text-primary); border: 1px solid rgba(255,255,255,0.08); }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
        }

        .card-title {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .card-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .card-value .unit {
            font-size: 1.1rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .note {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-top: 0.8rem;
        }

        @media (max-width: 768px) {
            .container { padding: 1.5rem; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>MobileMoney</h1>
        <a href="<?= site_url('gains/historique') ?>">Historique global</a>
    </nav>

    <div class="container">
        <div class="header">
            <h2>Calcul des gains opérateur</h2>
            <p>Total des frais générés par les opérations validées de type retrait et transfert.</p>
        </div>

        <div class="actions">
            <a class="btn btn-primary" href="<?= site_url('gains/historique') ?>">Voir l'historique global</a>
            <a class="btn btn-secondary" href="<?= site_url('operateur') ?>">Retour au dashboard</a>
        </div>

        <?php
            $gainsByCode = [];
            foreach (($gainsParType ?? []) as $gain) {
                $gainsByCode[$gain['code']] = $gain;
            }
            $retrait = $gainsByCode['RETRAIT'] ?? ['nombre_operations' => 0, 'gain_total' => 0, 'libelle' => 'Retrait'];
            $transfert = $gainsByCode['TRANSFERT'] ?? ['nombre_operations' => 0, 'gain_total' => 0, 'libelle' => 'Transfert'];
        ?>

        <div class="grid">
            <div class="card">
                <div class="card-title">Retraits</div>
                <div class="card-value"><?= number_format((int) ($retrait['gain_total'] ?? 0), 0, ',', ' ') ?> <span class="unit">Ar</span></div>
                <div class="note"><?= number_format((int) ($retrait['nombre_operations'] ?? 0), 0, ',', ' ') ?> opération(s)</div>
            </div>

            <div class="card">
                <div class="card-title">Transferts</div>
                <div class="card-value"><?= number_format((int) ($transfert['gain_total'] ?? 0), 0, ',', ' ') ?> <span class="unit">Ar</span></div>
                <div class="note"><?= number_format((int) ($transfert['nombre_operations'] ?? 0), 0, ',', ' ') ?> opération(s)</div>
            </div>

            <div class="card" style="grid-column: 1 / -1; background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2)); border-color: rgba(139, 92, 246, 0.3);">
                <div class="card-title" style="color: #e2e8f0;">Gain global</div>
                <div class="card-value" style="font-size: 3.5rem; color: #fff;">
                    <?= number_format((int) ($gainGlobal ?? 0), 0, ',', ' ') ?> <span class="unit" style="color: rgba(255,255,255,0.7);">Ar</span>
                </div>
                <div class="note" style="color: rgba(255,255,255,0.75);">Somme des frais validés sur les retraits et transferts.</div>
            </div>
        </div>
    </div>
</body>
</html>