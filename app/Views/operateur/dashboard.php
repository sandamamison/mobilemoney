<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Opérateur</title>
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

        .container {
            flex: 1;
            padding: 3rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .header {
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

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .card:hover::before {
            opacity: 1;
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
            font-size: 1.2rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>MobileMoney</h1>
        <div style="display:flex;align-items:center;gap:1.5rem;">
            <a href="<?= site_url('gains') ?>" style="color:var(--text-secondary);text-decoration:none;font-weight:600;">Gains</a>
            <a href="<?= site_url('gains/historique') ?>" style="color:var(--text-secondary);text-decoration:none;font-weight:600;">Historique</a>
            <div style="font-weight: 600; color: var(--text-secondary);">ESPACE OPÉRATEUR</div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h2>Tableau de bord</h2>
            <p>Vue d'ensemble des activités de la plateforme.</p>
        </div>

        <div class="grid">
            <div class="card">
                <div class="card-title">Clients</div>
                <div class="card-value"><?= number_format($nombreDeClients ?? 0, 0, ',', ' ') ?></div>
            </div>

            <div class="card">
                <div class="card-title">Comptes</div>
                <div class="card-value"><?= number_format($nombreDeComptes ?? 0, 0, ',', ' ') ?></div>
            </div>
            
            <div class="card">
                <div class="card-title">Opérations</div>
                <div class="card-value"><?= number_format($nombreDOperations ?? 0, 0, ',', ' ') ?></div>
            </div>

            <div class="card">
                <div class="card-title">Solde total</div>
                <div class="card-value"><?= number_format($totalDesSoldes ?? 0, 0, ',', ' ') ?> <span class="unit">Ar</span></div>
            </div>

            <div class="card" style="grid-column: 1 / -1; background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2)); border-color: rgba(139, 92, 246, 0.3);">
                <div class="card-title" style="color: #e2e8f0;">Gains Frais</div>
                <div class="card-value" style="font-size: 3.5rem; color: #fff;">
                    <?= number_format($totalDesFrais ?? 0, 0, ',', ' ') ?> <span class="unit" style="color: rgba(255,255,255,0.7);">Ar</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>