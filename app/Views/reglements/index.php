<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montants à envoyer · MobiCash</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
    <style>
        :root {
            --bg:       #0f172a;
            --card-bg:  rgba(30,41,59,0.72);
            --text:     #f8fafc;
            --muted:    #94a3b8;
            --border:   rgba(255,255,255,0.07);
            --warn:     #f59e0b;
            --danger:   #ef4444;
            --accent:   #6366f1;
            --accent2:  #8b5cf6;
            --sky:      #0ea5e9;
            --emerald:  #10b981;
        }
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            background-image:
                radial-gradient(circle at 80% 8%,  rgba(245,158,11,.14), transparent 42%),
                radial-gradient(circle at 12% 90%, rgba(239,68,68,.1),  transparent 42%);
            color: var(--text);
            min-height: 100vh;
        }

        .page-wrap { max-width: 1100px; margin: 0 auto; padding: 2.5rem 2rem 5rem; }

        /* header */
        .page-eyebrow {
            font-size: .73rem; font-weight: 800; letter-spacing: .1em;
            text-transform: uppercase; margin-bottom: .45rem; color: #fbbf24;
        }
        .page-title {
            margin: 0 0 .35rem; font-size: 2.2rem; font-weight: 800; letter-spacing: -.04em;
        }
        .page-sub { margin: 0 0 2.5rem; color: var(--muted); font-size: .93rem; }

        /* summary strip */
        .summary-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2.5rem;
        }
        @media (max-width: 860px) { .summary-strip { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 520px) { .summary-strip { grid-template-columns: 1fr; } }

        .strip-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.4rem 1.3rem;
            position: relative;
            overflow: hidden;
        }
        .strip-card::before {
            content: ''; position: absolute; top: 0; left: 0;
            width: 100%; height: 3px;
        }
        .sc-ops::before     { background: linear-gradient(90deg, var(--accent), var(--accent2)); }
        .sc-montant::before { background: linear-gradient(90deg, var(--sky), #38bdf8); }
        .sc-comm::before    { background: linear-gradient(90deg, var(--warn), #fbbf24); }
        .sc-total::before   { background: linear-gradient(90deg, var(--danger), #f87171); }

        .sc-icon {
            width: 34px; height: 34px; border-radius: 9px;
            display: grid; place-items: center; font-size: .85rem;
            margin-bottom: .9rem;
        }
        .sc-ops .sc-icon     { background: rgba(99,102,241,.14);  color: #818cf8; }
        .sc-montant .sc-icon { background: rgba(14,165,233,.14);  color: #38bdf8; }
        .sc-comm .sc-icon    { background: rgba(245,158,11,.14);  color: #fbbf24; }
        .sc-total .sc-icon   { background: rgba(239,68,68,.12);   color: #f87171; }

        .sc-label {
            font-size: .7rem; font-weight: 700; letter-spacing: .07em;
            text-transform: uppercase; color: var(--muted); margin-bottom: .35rem;
        }
        .sc-value {
            font-size: 1.65rem; font-weight: 800; letter-spacing: -.03em; line-height: 1;
        }
        .sc-value small { font-size: .78rem; font-weight: 500; color: var(--muted); margin-left: .15rem; }

        /* hero total */
        .hero-total {
            background: linear-gradient(135deg, rgba(239,68,68,.15), rgba(245,158,11,.12));
            border: 1px solid rgba(239,68,68,.22);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap;
            margin-bottom: 2.5rem;
            position: relative; overflow: hidden;
        }
        .hero-total::before {
            content: ''; position: absolute; top: 0; left: 0;
            width: 100%; height: 3px;
            background: linear-gradient(90deg, #ef4444, #f59e0b);
        }
        .ht-left { flex: 1; min-width: 200px; }
        .ht-eyebrow {
            font-size: .7rem; font-weight: 800; letter-spacing: .12em;
            text-transform: uppercase; color: #fca5a5; margin-bottom: .4rem;
        }
        .ht-value {
            font-size: 2.8rem; font-weight: 800; letter-spacing: -.05em;
            color: #fff; line-height: 1;
        }
        .ht-value small { font-size: 1rem; color: rgba(255,255,255,.5); margin-left: .25rem; }
        .ht-sub { color: #fecaca; font-size: .82rem; margin-top: .5rem; }

        .ht-formula {
            background: rgba(0,0,0,.2); border-radius: 12px; padding: 1.2rem 1.5rem;
            font-family: monospace; font-size: .85rem; color: #fca5a5;
            line-height: 1.7;
        }
        .ht-formula .val { color: #fff; font-weight: 700; }

        /* section label */
        .section-label {
            display: flex; align-items: center; gap: .6rem;
            font-size: .76rem; font-weight: 800; letter-spacing: .1em;
            text-transform: uppercase; color: var(--muted); margin: 0 0 1rem;
        }
        .section-label .dot {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }

        /* detail card */
        .detail-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,.2);
        }
        .detail-card-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: .55rem;
            font-size: .8rem; font-weight: 700; letter-spacing: .06em;
            text-transform: uppercase; color: var(--muted);
        }

        /* table */
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: rgba(255,255,255,.025); color: var(--muted);
            font-size: .73rem; font-weight: 700; letter-spacing: .07em;
            text-transform: uppercase; padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border); text-align: left;
        }
        thead th:not(:first-child) { text-align: right; }
        tbody td {
            padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--border);
            font-size: .93rem; vertical-align: middle;
        }
        tbody td:not(:first-child) { text-align: right; font-variant-numeric: tabular-nums; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background .18s; }
        tbody tr:hover td { background: rgba(255,255,255,.022); }

        tfoot td {
            padding: 1rem 1.5rem; background: rgba(255,255,255,.03);
            border-top: 1px solid var(--border); font-weight: 700; font-size: .9rem;
        }
        tfoot td:not(:first-child) { text-align: right; }

        /* operator badge */
        .op-badge {
            display: inline-flex; align-items: center; gap: .45rem;
            font-weight: 700; font-size: .95rem;
        }
        .op-dot {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }

        /* amounts */
        .amount     { font-weight: 700; }
        .amount-sky { color: #38bdf8; }
        .amount-warn{ color: #fbbf24; }
        .amount-red { color: #f87171; }

        /* commission chip */
        .comm-chip {
            display: inline-flex; align-items: center; gap: .2rem;
            background: rgba(245,158,11,.12); color: #fbbf24;
            border: 1px solid rgba(245,158,11,.22);
            border-radius: 20px; padding: .18rem .6rem;
            font-size: .77rem; font-weight: 700;
        }

        /* frais retrait pill */
        .frais-pill {
            display: inline-flex; align-items: center; gap: .25rem;
            background: rgba(148,163,184,.1); color: var(--muted);
            border: 1px solid rgba(148,163,184,.15);
            border-radius: 20px; padding: .15rem .55rem;
            font-size: .75rem; font-weight: 600;
        }

        /* empty */
        .empty-state {
            text-align: center; padding: 4rem 2rem; color: var(--muted);
        }
        .empty-state i { font-size: 2.8rem; opacity: .35; display: block; margin-bottom: 1rem; }
        .empty-state p { margin: 0; }

        /* links */
        .quick-links {
            display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 2.5rem;
        }
        .ql-btn {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .55rem 1.1rem; border-radius: 999px;
            font-size: .83rem; font-weight: 600; text-decoration: none;
            border: 1px solid var(--border); color: var(--muted);
            background: rgba(255,255,255,.04); transition: all .2s;
        }
        .ql-btn:hover { color: var(--text); background: rgba(255,255,255,.09); }
    </style>
</head>
<body>
    <?= view('operateur/_header') ?>

    <div class="page-wrap">
        <div class="page-eyebrow"><i class="fa-solid fa-paper-plane"></i> Finances · Règlements</div>
        <h1 class="page-title">Montants à envoyer</h1>
        <p class="page-sub">Récapitulatif des montants à régler à chaque opérateur externe.</p>

        <!-- Quick links -->
        <div class="quick-links">
            <a href="<?= site_url('gains') ?>" class="ql-btn">
                <i class="fa-solid fa-chart-column"></i> Gains
            </a>
            <a href="<?= site_url('autres-operateurs') ?>" class="ql-btn">
                <i class="fa-solid fa-tower-broadcast"></i> Autres opérateurs
            </a>
            <a href="<?= site_url('operateur') ?>" class="ql-btn">
                <i class="fa-solid fa-table-columns"></i> Dashboard
            </a>
        </div>

        <?php if (!empty($reglements)): ?>

        <!-- ────────────────────── -->
        <!-- HERO — Total à régler -->
        <!-- ────────────────────── -->
        <div class="hero-total">
            <div class="ht-left">
                <div class="ht-eyebrow"><i class="fa-solid fa-triangle-exclamation"></i> Total à régler à tous les opérateurs</div>
                <div class="ht-value">
                    <?= number_format($totalARegler ?? 0, 0, ',', ' ') ?>
                    <small>Ar</small>
                </div>
                <div class="ht-sub">
                    Somme des montants transférés + commissions dues aux <?= count($reglements) ?> opérateur(s).
                </div>
            </div>
            <div class="ht-formula">
                Montants envoyés : <span class="val"><?= number_format($totalMontant ?? 0, 0, ',', ' ') ?> Ar</span><br>
                Commissions dues : <span class="val">+ <?= number_format($totalCommission ?? 0, 0, ',', ' ') ?> Ar</span><br>
                Frais de retrait : <span class="val">+ 0 Ar</span><br>
                <span style="border-top:1px solid rgba(255,255,255,.15);display:block;padding-top:.35rem;margin-top:.35rem;">
                    Total à régler : <span class="val" style="color:#f87171;"><?= number_format($totalARegler ?? 0, 0, ',', ' ') ?> Ar</span>
                </span>
            </div>
        </div>

        <!-- ────────────────────── -->
        <!-- 4 cartes résumé       -->
        <!-- ────────────────────── -->
        <div class="summary-strip">
            <div class="strip-card sc-ops">
                <div class="sc-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                <div class="sc-label">Transferts externes</div>
                <div class="sc-value"><?= number_format($totalOperations ?? 0, 0, ',', ' ') ?></div>
            </div>
            <div class="strip-card sc-montant">
                <div class="sc-icon"><i class="fa-solid fa-paper-plane"></i></div>
                <div class="sc-label">Montants envoyés</div>
                <div class="sc-value"><?= number_format($totalMontant ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
            </div>
            <div class="strip-card sc-comm">
                <div class="sc-icon"><i class="fa-solid fa-percent"></i></div>
                <div class="sc-label">Commissions</div>
                <div class="sc-value"><?= number_format($totalCommission ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
            </div>
            <div class="strip-card sc-total">
                <div class="sc-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                <div class="sc-label">Total à régler</div>
                <div class="sc-value" style="color:#f87171;"><?= number_format($totalARegler ?? 0, 0, ',', ' ') ?> <small>Ar</small></div>
            </div>
        </div>

        <!-- ────────────────────────── -->
        <!-- Tableau détail par opérateur -->
        <!-- ────────────────────────── -->
        <div class="section-label">
            <span class="dot" style="background:var(--warn);"></span>
            Détail par opérateur
        </div>

        <div class="detail-card">
            <div class="detail-card-header">
                <i class="fa-solid fa-building-columns" style="color:var(--warn);"></i>
                Règlement à effectuer par opérateur
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Opérateur</th>
                        <th>Commission</th>
                        <th>Nb transferts</th>
                        <th>Montants envoyés</th>
                        <th>Frais de retrait</th>
                        <th>Commission due</th>
                        <th>Total à régler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $colors = ['#6366f1','#0ea5e9','#f59e0b','#ef4444','#10b981','#8b5cf6','#ec4899'];
                        $ci = 0;
                    ?>
                    <?php foreach ($reglements as $r): ?>
                        <?php $dotColor = $colors[$ci % count($colors)]; $ci++; ?>
                        <tr>
                            <td>
                                <span class="op-badge">
                                    <span class="op-dot" style="background:<?= $dotColor ?>;"></span>
                                    <?= esc($r['operateur_nom']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="comm-chip">
                                    <i class="fa-solid fa-percent" style="font-size:.68rem;"></i>
                                    <?= number_format((float)$r['taux_commission'], 2) ?>
                                </span>
                            </td>
                            <td class="amount">
                                <?= number_format((int)$r['nombre_operations'], 0, ',', ' ') ?>
                            </td>
                            <td class="amount amount-sky">
                                <?= number_format((int)$r['montant_total'], 0, ',', ' ') ?> Ar
                            </td>
                            <td>
                                <span class="frais-pill">
                                    <i class="fa-solid fa-minus" style="font-size:.6rem;"></i> 0 Ar
                                </span>
                            </td>
                            <td class="amount amount-warn">
                                + <?= number_format((int)$r['commission_due'], 0, ',', ' ') ?> Ar
                            </td>
                            <td class="amount amount-red" style="font-size:1.05rem;">
                                <?= number_format((int)$r['total_a_regler'], 0, ',', ' ') ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="color:var(--muted);font-size:.82rem;font-weight:700;">
                            TOTAL — <?= count($reglements) ?> opérateur(s)
                        </td>
                        <td class="amount"><?= number_format($totalOperations, 0, ',', ' ') ?></td>
                        <td class="amount" style="color:#38bdf8;"><?= number_format($totalMontant, 0, ',', ' ') ?> Ar</td>
                        <td style="color:var(--muted);">0 Ar</td>
                        <td class="amount" style="color:#fbbf24;">+ <?= number_format($totalCommission, 0, ',', ' ') ?> Ar</td>
                        <td class="amount" style="color:#f87171;font-size:1.05rem;"><?= number_format($totalARegler, 0, ',', ' ') ?> Ar</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php else: ?>
            <div class="detail-card">
                <div class="empty-state">
                    <i class="fa-solid fa-paper-plane"></i>
                    <p>Aucun transfert externe enregistré pour l'instant.</p>
                    <p style="font-size:.82rem;margin-top:.5rem;color:rgba(148,163,184,.6);">
                        Les montants à régler apparaîtront ici après des transferts vers d'autres opérateurs.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
