<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autres Opérateurs · MobiCash</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
    <style>
        :root {
            --bg:         #0f172a;
            --card-bg:    rgba(30, 41, 59, 0.72);
            --text:       #f8fafc;
            --muted:      #94a3b8;
            --accent:     #6366f1;
            --accent2:    #8b5cf6;
            --success:    #10b981;
            --danger:     #ef4444;
            --warn:       #f59e0b;
            --border:     rgba(255,255,255,0.07);
            --input-bg:   rgba(15,23,42,0.6);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            background-image:
                radial-gradient(circle at 80% 10%, rgba(99,102,241,.18), transparent 45%),
                radial-gradient(circle at 10% 90%, rgba(139,92,246,.15), transparent 45%);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── main layout ── */
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
        .page-header h1 {
            margin: 0 0 .35rem;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -.04em;
        }
        .page-header p { margin: 0; color: var(--muted); font-size: .95rem; }

        /* ── button ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.4rem;
            border-radius: 10px;
            font-size: .93rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: transform .2s, opacity .2s, box-shadow .2s;
        }
        .btn:hover { transform: translateY(-2px); opacity: .92; }
        .btn-primary { background: linear-gradient(135deg, var(--accent), var(--accent2)); color: #fff; box-shadow: 0 4px 18px rgba(99,102,241,.35); }
        .btn-sm { padding: .45rem 1rem; font-size: .82rem; border-radius: 8px; }
        .btn-edit { background: rgba(99,102,241,.15); color: #818cf8; border: 1px solid rgba(99,102,241,.25); }
        .btn-edit:hover { background: rgba(99,102,241,.28); }
        .btn-toggle-on  { background: rgba(16,185,129,.14); color: #34d399; border: 1px solid rgba(16,185,129,.25); }
        .btn-toggle-on:hover  { background: rgba(239,68,68,.18); color: #f87171; border-color: rgba(239,68,68,.3); }
        .btn-toggle-off { background: rgba(239,68,68,.14); color: #f87171; border: 1px solid rgba(239,68,68,.25); }
        .btn-toggle-off:hover { background: rgba(16,185,129,.18); color: #34d399; border-color: rgba(16,185,129,.3); }
        .btn-danger { background: rgba(239,68,68,.15); color: #f87171; border: 1px solid rgba(239,68,68,.25); }
        .btn-danger:hover { background: rgba(239,68,68,.30); }

        /* ── alerts ── */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: .93rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .alert-success { background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.3); color: #34d399; }
        .alert-error   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #f87171; }

        /* ── card table ── */
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0,0,0,.25);
        }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: rgba(255,255,255,.03);
            color: var(--muted);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        tbody td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            font-size: .95rem;
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background .2s; }
        tbody tr:hover td { background: rgba(255,255,255,.025); }

        /* ── badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .85rem;
            border-radius: 20px;
            font-size: .8rem;
            font-weight: 600;
        }
        .badge-active   { background: rgba(16,185,129,.15);  color: #34d399; border: 1px solid rgba(16,185,129,.25); }
        .badge-inactive { background: rgba(239,68,68,.12);   color: #f87171; border: 1px solid rgba(239,68,68,.2); }

        /* ── commission pill ── */
        .commission-pill {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            background: rgba(245,158,11,.12);
            color: #fbbf24;
            border: 1px solid rgba(245,158,11,.25);
            padding: .3rem .75rem;
            border-radius: 20px;
            font-size: .85rem;
            font-weight: 700;
        }

        /* ── actions ── */
        .actions { display: flex; gap: .65rem; align-items: center; flex-wrap: wrap; }

        /* ── empty state ── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: .4; display: block; }
        .empty-state p { margin: 0 0 1.5rem; font-size: 1rem; }

        @media (max-width: 700px) {
            .page-header { flex-direction: column; align-items: flex-start; }
            thead th:nth-child(1), tbody td:nth-child(1) { display: none; }
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
                <div style="color:#818cf8;font-size:.75rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;margin-bottom:.5rem;">
                    Configuration · Interopérabilité
                </div>
                <h1>Autres Opérateurs</h1>
                <p>Gérez les opérateurs tiers et leurs commissions sur les transferts.</p>
            </div>
            <a href="<?= site_url('autres-operateurs/create') ?>" class="btn btn-primary" id="btn-add-operateur">
                <i class="fa-solid fa-plus"></i> Ajouter un opérateur
            </a>
        </div>

        <!-- Table -->
        <div class="card">
            <?php if (!empty($operateurs) && is_array($operateurs)): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom de l'opérateur</th>
                        <th>Commission</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operateurs as $op): ?>
                    <tr>
                        <td style="color:var(--muted);">#<?= esc($op['id']) ?></td>
                        <td>
                            <span style="font-weight:700;font-size:1.05rem;"><?= esc($op['nom']) ?></span>
                        </td>
                        <td>
                            <span class="commission-pill">
                                <i class="fa-solid fa-percent" style="font-size:.75rem;"></i>
                                <?= number_format((float)$op['commission'], 2) ?>&nbsp;%
                            </span>
                        </td>
                        <td>
                            <?php if ($op['actif'] == 1): ?>
                                <span class="badge badge-active"><i class="fa-solid fa-circle" style="font-size:.5rem;"></i> Actif</span>
                            <?php else: ?>
                                <span class="badge badge-inactive"><i class="fa-solid fa-circle" style="font-size:.5rem;"></i> Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <!-- Modifier -->
                                <a href="<?= site_url('autres-operateurs/edit/' . $op['id']) ?>"
                                   class="btn btn-sm btn-edit"
                                   id="btn-edit-<?= $op['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> Modifier
                                </a>

                                <!-- Toggle actif/inactif -->
                                <form action="<?= site_url('autres-operateurs/toggle/' . $op['id']) ?>" method="post" style="margin:0;">
                                    <?= csrf_field() ?>
                                    <?php if ($op['actif'] == 1): ?>
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
                                <form action="<?= site_url('autres-operateurs/delete') ?>" method="post"
                                      onsubmit="return confirm('Supprimer cet opérateur ?');" style="margin:0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= esc($op['id']) ?>">
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
                    <i class="fa-solid fa-tower-broadcast"></i>
                    <p>Aucun autre opérateur enregistré pour l'instant.</p>
                    <a href="<?= site_url('autres-operateurs/create') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> Ajouter le premier opérateur
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
