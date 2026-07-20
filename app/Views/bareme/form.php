<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barème de Frais</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
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
        .navbar a:hover { color: var(--text-primary); }

        .container {
            flex: 1;
            padding: 3rem;
            max-width: 680px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header { margin-bottom: 2.5rem; text-align: center; }
        .header h2 { font-size: 2.5rem; font-weight: 700; margin: 0 0 0.5rem 0; }
        .header p { color: var(--text-secondary); font-size: 1.1rem; margin: 0; }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
        }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-weight: 500; font-size: 0.95rem; }

        .form-control {
            width: 100%; padding: 1rem;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 1.1rem;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-control:focus { outline: none; border-color: var(--gradient-start); box-shadow: 0 0 0 3px rgba(59,130,246,0.2); }
        .form-control::placeholder { color: rgba(148,163,184,0.5); }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 0.65rem;
            padding-right: 2.5rem;
            cursor: pointer;
        }
        select.form-control option { background: var(--bg-color); color: var(--text-primary); }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

        .actions { display: flex; gap: 1rem; margin-top: 2.5rem; }
        .btn { flex: 1; padding: 1rem; border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.3s; border: none; }
        .btn-primary { background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end)); color: white; box-shadow: 0 4px 15px rgba(59,130,246,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59,130,246,0.4); }
        .btn-secondary { background: rgba(255,255,255,0.05); color: var(--text-primary); border: 1px solid rgba(255,255,255,0.1); }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
            .container { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>MobileMoney</h1>
        <div style="display: flex; gap: 2rem; align-items: center;">
            <a href="<?= site_url('operateur') ?>">Dashboard</a>
            <a href="<?= site_url('prefixe') ?>">Préfixes</a>
            <a href="<?= site_url('typesoperation') ?>">Opérations</a>
            <a href="<?= site_url('bareme') ?>">Barèmes</a>
            <div style="font-weight: 600; color: var(--text-secondary);">ESPACE OPÉRATEUR</div>
        </div>
    </nav>

    <div class="container">
        <?php
        $isEdit = isset($bareme) && !empty($bareme);
        $action = $isEdit
            ? site_url('bareme/update/' . esc($bareme['id']))
            : site_url('bareme/store');
        $title = $isEdit ? 'Modifier le Barème' : 'Ajouter un Barème';
        $desc  = $isEdit ? 'Modifiez la tranche de frais sélectionnée.' : 'Définissez une nouvelle tranche de frais.';
        ?>

        <div class="header">
            <h2><?= $title ?></h2>
            <p><?= $desc ?></p>
        </div>

        <div class="card">
            <form action="<?= $action ?>" method="post">

                <div class="form-group">
                    <label for="type_operation_id">Type d'opération</label>
                    <select name="type_operation_id" id="type_operation_id" class="form-control" required <?= $isEdit ? 'disabled' : '' ?>>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($typesOperations as $type): ?>
                            <option value="<?= esc($type['id']) ?>"
                                <?= ($isEdit && $bareme['type_operation_id'] == $type['id']) ? 'selected' : '' ?>>
                                <?= esc($type['libelle']) ?> (<?= esc($type['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($isEdit): ?>
                        <!-- Champ caché pour envoyer la valeur même si le select est disabled -->
                        <input type="hidden" name="type_operation_id" value="<?= esc($bareme['type_operation_id']) ?>">
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="montant_min">Montant minimum (Ar)</label>
                        <input type="number" name="montant_min" id="montant_min" class="form-control"
                               placeholder="Ex: 1000" required min="0"
                               value="<?= $isEdit ? esc($bareme['montant_min']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="montant_max">Montant maximum (Ar)</label>
                        <input type="number" name="montant_max" id="montant_max" class="form-control"
                               placeholder="Ex: 5000" required min="0"
                               value="<?= $isEdit ? esc($bareme['montant_max']) : '' ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="frais">Frais appliqués (Ar)</label>
                        <input type="number" name="frais" id="frais" class="form-control"
                               placeholder="Ex: 200" required min="0"
                               value="<?= $isEdit ? esc($bareme['frais']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="actif">Statut</label>
                        <select name="actif" id="actif" class="form-control">
                            <option value="1" <?= ($isEdit && $bareme['actif'] == 1) ? 'selected' : '' ?>>Actif</option>
                            <option value="0" <?= ($isEdit && $bareme['actif'] == 0) ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="actions">
                    <a href="<?= site_url('bareme') ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
