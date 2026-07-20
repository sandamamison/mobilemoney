<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gains Opérateur · MobiCash</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
    <style>
        :root {
            --bg:        #0f172a;
            --card-bg:   rgba(30, 41, 59, 0.72);
            --text:      #f8fafc;
            --muted:     #94a3b8;
            --border:    rgba(255,255,255,0.07);
            /* couleurs sémantiques */
            --c-retrait:  #f59e0b; /* amber  — retraits       */
            --c-interne:  #6366f1; /* indigo — transferts int  */
            --c-externe:  #0ea5e9; /* sky    — transferts ext  */
            --c-commission: #ef4444; /* red  — commissions dues */
            --c-net:      #10b981; /* emerald — gain net total */
        }
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            background-image:
                radial-gradient(circle at 80% 5%,  rgba(99,102,241,.18), transparent 42%),
                radial-gradient(circle at 10% 90%, rgba(14,165,233,.14), transparent 42%);
            color: var(--text);
            min-height: 100vh;
        }

        .page-wrap { max-width: 1140px; margin: 0 auto; padding: 2.5rem 2rem 5rem; }

        /* ── page header ── */
        .page-eyebrow {
            font-size: .73rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: .45rem;
            color: #818cf8;
        }
        .page-title {
            margin: 0 0 .35rem;
            font-size: 2.3rem;
            font-weight: 800;
            letter-spacing: -.04em;
        }
        .page-sub { margin: 0 0 2.5rem; color: var(--muted); font-size: .95rem; }

        /* ── section label ── */
        .section-label {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin: 2.4rem 0 1rem;
        }
        .section-label .dot {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }

        /* ── summary grid (top 4 cards) ── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: .5rem;
        }
        @media (max-width: 900px) { .summary-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 540px) { .summary-grid { grid-template-columns: 1fr; } }

        /* ── metric card ── */
        .metric-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.6rem 1.5rem;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
        }
        .metric-card:hover { transform: translateY(-2px); }
        .metric-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
        }
        .mc-retrait::before   { background: linear-gradient(90deg, var(--c-retrait), #fbbf24); }
        .mc-interne::before   { background: linear-gradient(90deg, var(--c-interne), #818cf8); }
        .mc-externe::before   { background: linear-gradient(90deg, var(--c-externe), #38bdf8); }
        .mc-commission::before{ background: linear-gradient(90deg, var(--c-commission), #f87171); }

        .mc-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: grid; place-items: center;
            font-size: .95rem;
            margin-bottom: 1.1rem;
        }
        .mc-retrait   .mc-icon { background: rgba(245,158,11,.16); color: var(--c-retrait); }
        .mc-interne   .mc-icon { background: rgba(99,102,241,.16);  color: var(--c-interne); }
        .mc-externe   .mc-icon { background: rgba(14,165,233,.16);  color: var(--c-externe); }
        .mc-commission.mc-icon { background: rgba(239,68,68,.14);   color: var(--c-commission); }

        .mc-label {
            font-size: .73rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .45rem;
        }
        .mc-value {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1;
        }
        .mc-value small { font-size: .85rem; font-weight: 500; color: var(--muted); margin-left: .2rem; }
        .mc-ops { margin-top: .55rem; font-size: .8rem; color: var(--muted); }

        /* ── hero net card ── */
        .hero-card {
            background: linear-gradient(135deg, rgba(16,185,129,.18), rgba(6,78,59,.35));
            border: 1px solid rgba(16,185,129,.25);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .hero-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
            background: linear-gradient(90deg, #10b981, #34d399);
        }
        .hero-left { flex: 1; min-width: 180px; }
        .hero-eyebrow {
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #6ee7b7;
            margin-bottom: .4rem;
        }
        .hero-value {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -.05em;
            color: #fff;
            line-height: 1;
        }
        .hero-value small { font-size: 1.1rem; color: rgba(255,255,255,.55); margin-left: .3rem; }
        .hero-sub { color: #a7f3d0; font-size: .85rem; margin-top: .5rem; }

        .hero-breakdown {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .hb-item { text-align: right; }
        .hb-label { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #6ee7b7; margin-bottom: .25rem; }
        .hb-val { font-size: 1.4rem; font-weight: 800; color: #fff; letter-spacing: -.03em; }
        .hb-val small { font-size: .75rem; color: rgba(255,255,255,.5); }

        /* ── detail card (table) ── */
        .detail-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,.2);
        }
        .detail-card-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--muted);
        }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: rgba(255,255,255,.025);
            color: var(--muted);
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        thead th:not(:first-child) { text-align: right; }
        tbody td {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            font-size: .93rem;
            vertical-align: middle;
        }
        tbody td:not(:first-child) { text-align: right; font-variant-numeric: tabular-nums; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background .18s; }
        tbody tr:hover td { background: rgba(255,255,255,.022); }

        /* operator name badge */
        .op-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-weight: 700;
            font-size: .95rem;
        }
        .op-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--c-externe);
            flex-shrink: 0;
        }

        /* amount cells */
        .amount        { font-weight: 700; }
        .amount-pos    { color: #34d399; }
        .amount-neg    { color: #f87171; }
        .amount-neutral{ color: #fbbf24; }

        /* commission chip */
        .commission-chip {
            display: inline-flex;
            align-items: center;
            gap: .2rem;
            background: rgba(239,68,68,.12);
            color: #f87171;
            border: 1px solid rgba(239,68,68,.2);
            border-radius: 20px;
            padding: .18rem .6rem;
            font-size: .78rem;
            font-weight: 700;
        }

        /* tfoot totals */
        tfoot td {
            padding: 1rem 1.5rem;
            background: rgba(255,255,255,.03);
            border-top: 1px solid var(--border);
            font-weight: 700;
            font-size: .9rem;
        }
        tfoot td:not(:first-child) { text-align: right; }

        /* empty state */
        .empty-row td {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--muted);
            font-size: .9rem;
        }
        .empty-row td i { display: block; font-size: 2rem; opacity: .3; margin-bottom: .75rem; }

        /* quick nav links */
        .quick-links {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .ql-btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem 1.1rem;
            border-radius: 999px;
            font-size: .83rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--muted);
            background: rgba(255,255,255,.04);
            transition: all .2s;
        }
        .ql-btn:hover { color: var(--text); background: rgba(255,255,255,.09); }
        .ql-btn.primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-color: transparent;
            color: #fff;
        }
        .ql-btn.primary:hover { opacity: .88; }
    </style>
</head>
<body>
    <?= view('operateur/_header') ?>

    <div class="page-wrap">
        <div class="page-eyebrow"><i class="fa-solid fa-chart-column"></i> Finances · Opérateur</div>
        <h1 class="page-title">Gains opérateur</h1>
        <p class="page-sub">Répartition détaillée des revenus par catégorie d'opération.</p>

        <!-- Quick links -->
        <div class="quick-links">
            <a href="<?= site_url('gains/historique') ?>" class="ql-btn primary">
                <i class="fa-solid fa-clock-rotate-left"></i> Historique global
            </a>
            <a href="<?= site_url('operateur') ?>" class="ql-btn">
                <i class="fa-solid fa-table-columns"></i> Dashboard
            </a>
            <a href="<?= site_url('autres-operateurs') ?>" class="ql-btn">
                <i class="fa-solid fa-tower-broadcast"></i> Autres opérateurs
            </a>
        </div>

        <!-- ──────────────────────────────────────────────── -->
        <!-- HERO — Gain net total                           -->
        <!-- ──────────────────────────────────────────────── -->
        <?php
            $gainNetTotal = ($gainsRetraits['gain_total'] ?? 0)
                          + ($gainsInternes['gain_total'] ?? 0)
                          + ($gainNetExternes ?? 0);
        ?>
        <div class="hero-card">
            <div class="hero-left">
                <div class="hero-eyebrow"><i class="fa-solid fa-circle-check"></i> Gain net total (après commissions)</div>
                <div class="hero-value">
                    <?= number_format($gainNetTotal, 0, ',', ' ') ?>
                    <small>Ar</small>
                </div>
                <div class="hero-sub">Total des frais collectés moins les commissions dues aux autres opérateurs.</div>
            </div>
            <div class="hero-breakdown">
                <div class="hb-item">
                    <div class="hb-label">Commissions dues</div>
                    <div class="hb-val" style="color:#f87171;">
                        −<?= number_format($totalCommissions ?? 0, 0, ',', ' ') ?>
                        <small>Ar</small>
                    </div>
                </div>
                <div class="hb-item">
                    <div class="hb-label">Frais bruts</div>
                    <div class="hb-val">
                        <?= number_format($gainGlobal ?? 0, 0, ',', ' ') ?>
                        <small>Ar</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ──────────────────────────────────────────────── -->
        <!-- RÉSUMÉ — 4 cartes                               -->
        <!-- ──────────────────────────────────────────────── -->
        <div class="section-label">
            <span class="dot" style="background:#6366f1;"></span> Vue d'ensemble par catégorie
        </div>

        <div class="summary-grid">
            <!-- Retraits -->
            <div class="metric-card mc-retrait">
                <div class="mc-icon"><i class="fa-solid fa-arrow-down-from-line"></i></div>
                <div class="mc-label">Frais de retrait</div>
                <div class="mc-value">
                    <?= number_format($gainsRetraits['gain_total'] ?? 0, 0, ',', ' ') ?>
                    <small>Ar</small>
                </div>
                <div class="mc-ops">
                    <?= number_format($gainsRetraits['nombre_operations'] ?? 0, 0, ',', ' ') ?> opération(s)
                </div>
            </div>

            <!-- Transferts internes -->
            <div class="metric-card mc-interne">
                <div class="mc-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                <div class="mc-label">Transferts internes</div>
                <div class="mc-value">
                    <?= number_format($gainsInternes['gain_total'] ?? 0, 0, ',', ' ') ?>
                    <small>Ar</small>
                </div>
                <div class="mc-ops">
                    <?= number_format($gainsInternes['nombre_operations'] ?? 0, 0, ',', ' ') ?> opération(s)
                </div>
            </div>

            <!-- Transferts externes (net) -->
            <div class="metric-card mc-externe">
                <div class="mc-icon"><i class="fa-solid fa-tower-broadcast"></i></div>
                <div class="mc-label">Transferts ext. (net)</div>
                <div class="mc-value">
                    <?= number_format($gainNetExternes ?? 0, 0, ',', ' ') ?>
                    <small>Ar</small>
                </div>
                <div class="mc-ops">
                    <?= count($gainsExternesParOp ?? []) ?> opérateur(s) externe(s)
                </div>
            </div>

            <!-- Commissions dues -->
            <div class="metric-card" style="border-color:rgba(239,68,68,.18);">
                <div class="mc-icon" style="background:rgba(239,68,68,.13);color:#f87171;">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div class="mc-label">Commissions dues</div>
                <div class="mc-value" style="color:#f87171;">
                    −<?= number_format($totalCommissions ?? 0, 0, ',', ' ') ?>
                    <small>Ar</small>
                </div>
                <div class="mc-ops" style="color:rgba(248,113,113,.65);">
                    À reverser aux opérateurs externes
                </div>
            </div>
        </div>

        <!-- ──────────────────────────────────────────────── -->
        <!-- DÉTAIL — Transferts externes par opérateur      -->
        <!-- ──────────────────────────────────────────────── -->
        <div class="section-label" style="margin-top:3rem;">
            <span class="dot" style="background:var(--c-externe);"></span>
            Commissions reçues par opérateur externe
        </div>

        <div class="detail-card">
            <div class="detail-card-header">
                <i class="fa-solid fa-tower-broadcast" style="color:var(--c-externe);"></i>
                Détail des transferts vers les autres opérateurs
            </div>
            <?php if (!empty($gainsExternesParOp)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Opérateur</th>
                        <th>Taux commission</th>
                        <th>Nb opérations</th>
                        <th>Montant transféré</th>
                        <th>Frais collectés</th>
                        <th>Commission due</th>
                        <th>Gain net</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $totalMontant = 0;
                        $totalFrais   = 0;
                        $totalComm    = 0;
                        $totalNet     = 0;
                        $totalOps     = 0;
                    ?>
                    <?php foreach ($gainsExternesParOp as $ligne): ?>
                        <?php
                            $commDue = (int)$ligne['frais_total'] - (int)$ligne['gain_net'];
                            $totalMontant += (int)$ligne['montant_total'];
                            $totalFrais   += (int)$ligne['frais_total'];
                            $totalComm    += $commDue;
                            $totalNet     += (int)$ligne['gain_net'];
                            $totalOps     += (int)$ligne['nombre_operations'];
                        ?>
                        <tr>
                            <td>
                                <span class="op-badge">
                                    <span class="op-dot"></span>
                                    <?= esc($ligne['operateur_nom']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="commission-chip">
                                    <i class="fa-solid fa-percent" style="font-size:.7rem;"></i>
                                    <?= number_format((float)$ligne['commission'], 2) ?>
                                </span>
                            </td>
                            <td class="amount">
                                <?= number_format((int)$ligne['nombre_operations'], 0, ',', ' ') ?>
                            </td>
                            <td class="amount amount-neutral">
                                <?= number_format((int)$ligne['montant_total'], 0, ',', ' ') ?> Ar
                            </td>
                            <td class="amount">
                                <?= number_format((int)$ligne['frais_total'], 0, ',', ' ') ?> Ar
                            </td>
                            <td class="amount amount-neg">
                                −<?= number_format($commDue, 0, ',', ' ') ?> Ar
                            </td>
                            <td class="amount amount-pos">
                                <?= number_format((int)$ligne['gain_net'], 0, ',', ' ') ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="color:var(--muted);font-size:.82rem;font-weight:600;">TOTAL</td>
                        <td><?= number_format($totalOps, 0, ',', ' ') ?></td>
                        <td style="color:#fbbf24;"><?= number_format($totalMontant, 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($totalFrais, 0, ',', ' ') ?> Ar</td>
                        <td style="color:#f87171;">−<?= number_format($totalComm, 0, ',', ' ') ?> Ar</td>
                        <td style="color:#34d399;"><?= number_format($totalNet, 0, ',', ' ') ?> Ar</td>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
                <table><tbody>
                    <tr class="empty-row">
                        <td>
                            <i class="fa-solid fa-tower-broadcast"></i>
                            Aucun transfert externe enregistré pour l'instant.<br>
                            <small style="font-size:.8rem;">Les transferts vers d'autres opérateurs apparaîtront ici.</small>
                        </td>
                    </tr>
                </tbody></table>
            <?php endif; ?>
        </div>

        <!-- ──────────────────────────────────────────────── -->
        <!-- DÉTAIL — Synthèse par type (vue existante)      -->
        <!-- ──────────────────────────────────────────────── -->
        <div class="section-label" style="margin-top:3rem;">
            <span class="dot" style="background:var(--c-retrait);"></span>
            Synthèse globale par type d'opération
        </div>

        <div class="detail-card">
            <div class="detail-card-header">
                <i class="fa-solid fa-list" style="color:var(--c-retrait);"></i>
                Frais bruts collectés (Retraits + Transferts)
            </div>
            <?php if (!empty($gainsParType)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Type d'opération</th>
                        <th>Nb opérations</th>
                        <th>Frais collectés</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gainsParType as $gain): ?>
                    <tr>
                        <td>
                            <span style="font-weight:700;"><?= esc($gain['libelle']) ?></span>
                            <code style="margin-left:.5rem;background:rgba(255,255,255,.06);border-radius:5px;padding:.1rem .4rem;font-size:.75rem;color:var(--muted);">
                                <?= esc($gain['code']) ?>
                            </code>
                        </td>
                        <td class="amount"><?= number_format((int)$gain['nombre_operations'], 0, ',', ' ') ?></td>
                        <td class="amount amount-neutral"><?= number_format((int)$gain['gain_total'], 0, ',', ' ') ?> Ar</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="1" style="color:var(--muted);font-size:.82rem;font-weight:600;">TOTAL BRUT</td>
                        <td></td>
                        <td style="color:#fbbf24;"><?= number_format($gainGlobal ?? 0, 0, ',', ' ') ?> Ar</td>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
                <table><tbody>
                    <tr class="empty-row">
                        <td><i class="fa-solid fa-chart-column"></i> Aucune opération avec frais enregistrée.</td>
                    </tr>
                </tbody></table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
