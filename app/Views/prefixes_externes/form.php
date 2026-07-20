<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un préfixe externe · MobiCash</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/backoffice.css?v=3') ?>">
    <style>
        :root {
            --bg:       #0f172a;
            --card-bg:  rgba(30,41,59,0.72);
            --text:     #f8fafc;
            --muted:    #94a3b8;
            --accent:   #0ea5e9;
            --accent2:  #6366f1;
            --border:   rgba(255,255,255,0.07);
            --input-bg: rgba(15,23,42,0.6);
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

        .page-wrap {
            max-width: 580px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: var(--muted);
            text-decoration: none;
            font-size: .88rem;
            margin-bottom: 2rem;
            transition: color .2s;
        }
        .back-link:hover { color: var(--text); }

        .page-title {
            margin: 0 0 .4rem;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -.04em;
        }
        .page-subtitle { color: var(--muted); margin: 0 0 2.5rem; font-size: .93rem; }

        /* card */
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 2.5rem;
            box-shadow: 0 12px 40px rgba(0,0,0,.25);
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
        }

        /* form */
        .form-group { margin-bottom: 1.6rem; }
        .form-group label {
            display: block;
            margin-bottom: .5rem;
            color: var(--muted);
            font-size: .87rem;
            font-weight: 600;
            letter-spacing: .03em;
        }
        .form-control {
            width: 100%;
            padding: .85rem 1rem;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: 1rem;
            font-family: inherit;
            transition: border-color .25s, box-shadow .25s;
            appearance: none;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(14,165,233,.18);
        }
        .form-control::placeholder { color: rgba(148,163,184,.42); }

        /* select custom arrow */
        select.form-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.1rem;
            padding-right: 2.5rem;
            cursor: pointer;
        }
        select.form-control option {
            background: #1e293b;
            color: #f8fafc;
        }

        /* hint */
        .field-hint {
            margin-top: .4rem;
            font-size: .79rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* alert errors */
        .alert-errors {
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.3);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            color: #f87171;
            font-size: .87rem;
        }
        .alert-errors ul { margin: .4rem 0 0 1rem; padding: 0; }
        .alert-errors li { margin-bottom: .25rem; }

        /* actions */
        .actions { display: flex; gap: 1rem; margin-top: 2rem; }
        .btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .9rem 1.2rem;
            border-radius: 10px;
            font-size: .96rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff;
            box-shadow: 0 4px 18px rgba(14,165,233,.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(14,165,233,.42); }
        .btn-secondary {
            background: rgba(255,255,255,.05);
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { background: rgba(255,255,255,.1); }

        /* no operators warning */
        .no-op-warn {
            display: flex;
            gap: .75rem;
            align-items: center;
            background: rgba(245,158,11,.1);
            border: 1px solid rgba(245,158,11,.28);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            color: #fbbf24;
            font-size: .88rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <?= view('operateur/_header') ?>

    <div class="page-wrap">
        <a href="<?= site_url('prefixes-externes') ?>" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Retour à la liste
        </a>

        <h1 class="page-title">Ajouter un préfixe</h1>
        <p class="page-subtitle">Associez un préfixe téléphonique à un opérateur externe.</p>

        <div class="card">
            <!-- Avertissement si aucun opérateur -->
            <?php if (empty($operateurs)): ?>
                <div class="no-op-warn">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size:1.2rem;flex-shrink:0;"></i>
                    <span>
                        Aucun opérateur externe n'est configuré.
                        <a href="<?= site_url('autres-operateurs/create') ?>" style="color:#fbbf24;font-weight:700;">
                            Créez-en un d'abord →
                        </a>
                    </span>
                </div>
            <?php endif; ?>

            <!-- Erreurs de validation -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert-errors">
                    <strong><i class="fa-solid fa-triangle-exclamation"></i> Veuillez corriger les erreurs :</strong>
                    <ul>
                        <?php foreach ((array) session()->getFlashdata('errors') as $e): ?>
                            <li><?= esc($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('prefixes-externes/store') ?>" method="post" id="form-create-prefixe">
                <?= csrf_field() ?>

                <!-- Préfixe -->
                <div class="form-group">
                    <label for="prefixe">Préfixe téléphonique</label>
                    <input type="text"
                           name="prefixe"
                           id="prefixe"
                           class="form-control"
                           placeholder="Ex : 032, 031, 020…"
                           value="<?= esc(old('prefixe')) ?>"
                           maxlength="5"
                           pattern="[0-9]{3,5}"
                           title="Le préfixe doit être composé de 3 à 5 chiffres"
                           required
                           autofocus
                           style="font-family:monospace;font-size:1.15rem;letter-spacing:.12em;">
                    <p class="field-hint">
                        <i class="fa-solid fa-circle-info"></i>
                        Saisissez uniquement les chiffres (3 à 5 caractères). Ex : <strong>032</strong>, <strong>031</strong>.
                    </p>
                </div>

                <!-- Opérateur associé -->
                <div class="form-group">
                    <label for="autre_operateur_id">Opérateur associé</label>
                    <select name="autre_operateur_id"
                            id="autre_operateur_id"
                            class="form-control"
                            required
                            <?= empty($operateurs) ? 'disabled' : '' ?>>
                        <option value="" disabled selected>— Sélectionner un opérateur —</option>
                        <?php foreach ($operateurs as $op): ?>
                            <option value="<?= esc($op['id']) ?>"
                                <?= old('autre_operateur_id') == $op['id'] ? 'selected' : '' ?>>
                                <?= esc($op['nom']) ?>
                                (<?= number_format((float)$op['commission'], 2) ?> %)
                                <?= $op['actif'] == 0 ? ' — Inactif' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="field-hint">
                        <i class="fa-solid fa-circle-info"></i>
                        Les numéros commençant par ce préfixe seront routés vers l'opérateur choisi.
                    </p>
                </div>

                <div class="actions">
                    <a href="<?= site_url('prefixes-externes') ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit"
                            class="btn btn-primary"
                            id="btn-submit-prefixe"
                            <?= empty($operateurs) ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
