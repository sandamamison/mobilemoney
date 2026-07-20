<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préfixes Externes · MobiCash</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
    <style>
        :root {
            --bg:        #0f172a;
            --card-bg:   rgba(30,41,59,0.72);
            --text:      #f8fafc;
            --muted:     #94a3b8;
            --accent:    #0ea5e9;
            --accent2:   #6366f1;
            --success:   #10b981;
            --danger:    #ef4444;
            --warn:      #f59e0b;
            --border:    rgba(255,255,255,0.07);
            --input-bg:  rgba(15,23,42,0.6);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            background-image:
                radial-gradient(circle at 75% 8%, rgba(14,165,233,.16), transparent 42%),
                radial-gradient(circle at 15% 88%, rgba(99,102,241,.14), transparent 42%);
            color: var(--text);
            min-height: 100vh;
        }

        .page-wrap { max-width: 1100px; margin: 0 auto; padding: 2.5rem 2rem 4rem; }

        /* ── page header ── */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .page-eyebrow {
            color: #38bdf8;
            font-size: .73rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: .45rem;
        }
        .page-header h1 {
            margin: 0 0 .35rem;
            font-size: 2.1rem;
            font-weight: 800;
            letter-spacing: -.04em;
        }
        .page-header p { margin: 0; color: var(--muted); font-size: .93rem; }

        /* ── buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.4rem;
            border-radius: 10px;
            font-size: .92rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: transform .2s, opacity .2s, box-shadow .2s;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-2px); opacity: .92; }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff;
            box-shadow: 0 4px 18px rgba(14,165,233,.3);
        }
        .btn-sm { padding: .42rem .95rem; font-size: .8rem; border-radius: 8px; }
        .btn-edit  { background: rgba(14,165,233,.14); color: #38bdf8; border: 1px solid rgba(14,165,233,.25); }
        .btn-edit:hover { background: rgba(14,165,233,.28); }
        .btn-toggle-on  { background: rgba(16,185,129,.14); color: #34d399; border: 1px solid rgba(16,185,129,.25); }
        .btn-toggle-on:hover  { background: rgba(239,68,68,.18); color: #f87171; border-color: rgba(239,68,68,.3); }
        .btn-toggle-off { background: rgba(239,68,68,.14); color: #f87171; border: 1px solid rgba(239,68,68,.25); }
        .btn-toggle-off:hover { background: rgba(16,185,129,.18); color: #34d399; border-color: rgba(16,185,129,.3); }
        .btn-danger { background: rgba(239,68,68,.14); color: #f87171; border: 1px solid rgba(239,68,68,.22); }
        .btn-danger:hover { background: rgba(239,68,68,.28); }

        /* ── alerts ── */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: .92rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .alert-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.28); color: #34d399; }
        .alert-error   { background: rgba(239,68,68,.1);  border: 1px solid rgba(239,68,68,.28);  color: #f87171; }

        /* ── card ── */
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0,0,0,.25);
        }

        /* ── table ── */
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: rgba(255,255,255,.03);
            color: var(--muted);
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        tbody td {
            padding: 1.15rem 1.5rem;
            border-bottom: 1px solid var(--border);
            font-size: .93rem;
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background .18s; }
        tbody tr:hover td { background: rgba(255,255,255,.022); }

        /* ── prefix chip ── */
        .prefix-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(14,165,233,.12);
            color: #38bdf8;
            border: 1px solid rgba(14,165,233,.22);
            padding: .32rem .9rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .12em;
            font-family: monospace;
        }

        /* ── operator tag ── */
        .op-tag {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(99,102,241,.12);
            color: #818cf8;
            border: 1px solid rgba(99,102,241,.2);
            padding: .28rem .75rem;
            border-radius: 20px;
            font-size: .82rem;
            font-weight: 600;
        }

        /* ── badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .32rem .8rem;
            border-radius: 20px;
            font-size: .79rem;
            font-weight: 600;
        }
        .badge-active   { background: rgba(16,185,129,.13); color: #34d399; border: 1px solid rgba(16,185,129,.23); }
        .badge-inactive { background: rgba(239,68,68,.11);  color: #f87171; border: 1px solid rgba(239,68,68,.2); }

        /* ── actions ── */
        .actions { display: flex; gap: .6rem; align-items: center; flex-wrap: wrap; }

        /* ── empty ── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }
        .empty-state i { font-size: 2.8rem; margin-bottom: 1rem; opacity: .38; display: block; }
        .empty-state p { margin: 0 0 1.5rem; }

        @media (max-width: 680px) {
            .page-header { flex-direction: column; align-items: flex-start; }
            thead th:first-child, tbody td:first-child { display: none; }
        }
    </style>
</head>
<body>
    <?= view('operateur/_header') ?>

    <div class="page-wrap">
        <!-- Alerts -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="page-header">
            <div>
                <div class="page-eyebrow"><i class="fa-solid fa-signal"></i> Configuration · Interopérabilité</div>
                <h1>Préfixes Externes</h1>
                <p>Associez les préfixes téléphoniques (032, 031…) aux autres opérateurs.</p>
            </div>
            <a href="<?= site_url('prefixes-externes/create') ?>" class="btn btn-primary" id="btn-add-prefixe">
                <i class="fa-solid fa-plus"></i> Ajouter un préfixe
            </a>
        </div>

        <!-- Table -->
        <div class="card">
            <?php if (!empty($prefixes) && is_array($prefixes)): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Préfixe</th>
                        <th>Opérateur associé</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prefixes as $p): ?>
                    <tr>
                        <td style="color:var(--muted);">#<?= esc($p['id']) ?></td>
                        <td>
                            <span class="prefix-chip">
                                <i class="fa-solid fa-hashtag" style="font-size:.7rem;"></i>
                                <?= esc($p['prefixe']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($p['operateur_nom'])): ?>
                                <span class="op-tag">
                                    <i class="fa-solid fa-tower-broadcast" style="font-size:.72rem;"></i>
                                    <?= esc($p['operateur_nom']) ?>
                                </span>
                            <?php else: ?>
                                <span style="color:var(--muted);font-size:.85rem;">— non lié</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['actif'] == 1): ?>
                                <span class="badge badge-active">
                                    <i class="fa-solid fa-circle" style="font-size:.45rem;"></i> Actif
                                </span>
                            <?php else: ?>
                                <span class="badge badge-inactive">
                                    <i class="fa-solid fa-circle" style="font-size:.45rem;"></i> Inactif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <!-- Modifier -->
                                <a href="<?= site_url('prefixes-externes/edit/' . $p['id']) ?>"
                                   class="btn btn-sm btn-edit"
                                   id="btn-edit-<?= $p['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> Modifier
                                </a>

                                <!-- Toggle actif/inactif -->
                                <form action="<?= site_url('prefixes-externes/toggle/' . $p['id']) ?>" method="post" style="margin:0;">
                                    <?= csrf_field() ?>
                                    <?php if ($p['actif'] == 1): ?>
                                        <button type="submit" class="btn btn-sm btn-toggle-on" title="Désactiver">
                                            <i class="fa-solid fa-toggle-on"></i> Actif
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-sm btn-toggle-off" title="Activer">
                                            <i class="fa-solid fa-toggle-off"></i> Inactif
                                        </button>
                                    <?php endif; ?>
                                </form>

                                <!-- Supprimer -->
                                <form action="<?= site_url('prefixes-externes/delete') ?>" method="post"
                                      onsubmit="return confirm('Supprimer ce préfixe ?');" style="margin:0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= esc($p['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fa-solid fa-sim-card"></i>
                    <p>Aucun préfixe externe configuré pour l'instant.</p>
                    <a href="<?= site_url('prefixes-externes/create') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> Ajouter le premier préfixe
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
