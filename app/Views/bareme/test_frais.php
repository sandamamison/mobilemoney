<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tester getFrais()</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a; --card: rgba(30,41,59,0.7); --text: #f8fafc;
            --muted: #94a3b8; --g1: #3b82f6; --g2: #8b5cf6;
            --input-bg: rgba(15,23,42,0.6); --border: rgba(255,255,255,0.1);
        }
        body { margin:0; font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; display:flex; flex-direction:column; }
        .navbar { padding:1.5rem 3rem; background:rgba(15,23,42,0.8); backdrop-filter:blur(12px); border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
        .navbar h1 { margin:0; font-size:1.5rem; font-weight:700; background:linear-gradient(to right,var(--g1),var(--g2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .navbar a { color:var(--muted); text-decoration:none; font-weight:500; transition:color 0.3s; }
        .navbar a:hover { color:var(--text); }
        .container { flex:1; padding:3rem; max-width:600px; margin:0 auto; width:100%; box-sizing:border-box; display:flex; flex-direction:column; justify-content:center; }
        .header { text-align:center; margin-bottom:2.5rem; }
        .header h2 { font-size:2.2rem; font-weight:700; margin:0 0 0.5rem 0; }
        .header p { color:var(--muted); margin:0; font-size:1.05rem; }
        .card { background:var(--card); backdrop-filter:blur(16px); border:1px solid var(--border); border-radius:16px; padding:2.5rem; box-shadow:0 10px 30px rgba(0,0,0,0.2); position:relative; overflow:hidden; }
        .card::before { content:''; position:absolute; top:0; left:0; width:100%; height:4px; background:linear-gradient(90deg,var(--g1),var(--g2)); }
        .form-group { margin-bottom:1.5rem; }
        label { display:block; margin-bottom:0.5rem; color:var(--muted); font-weight:500; font-size:0.95rem; }
        .form-control { width:100%; padding:1rem; background:var(--input-bg); border:1px solid var(--border); border-radius:8px; color:var(--text); font-size:1.1rem; box-sizing:border-box; transition:border-color 0.3s; }
        .form-control:focus { outline:none; border-color:var(--g1); box-shadow:0 0 0 3px rgba(59,130,246,0.2); }
        select.form-control { appearance:none; background-image:url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"); background-repeat:no-repeat; background-position:right 1rem center; background-size:0.65rem; padding-right:2.5rem; cursor:pointer; }
        select.form-control option { background:var(--bg); }
        button { width:100%; padding:1rem; background:linear-gradient(90deg,var(--g1),var(--g2)); color:white; border:none; border-radius:8px; font-size:1.1rem; font-weight:600; cursor:pointer; margin-top:0.5rem; transition:opacity 0.3s,transform 0.2s; }
        button:hover { opacity:0.9; transform:translateY(-2px); }
        button:disabled { opacity:0.5; cursor:not-allowed; transform:none; }

        #result-box {
            margin-top: 2rem;
            border-radius: 12px;
            padding: 1.5rem 2rem;
            text-align: center;
            display: none;
            transition: all 0.4s ease;
        }
        #result-box.found   { background:rgba(52,211,153,0.08); border:1px solid rgba(52,211,153,0.3); }
        #result-box.notfound { background:rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.3); }
        #result-box .label { color:var(--muted); font-size:0.9rem; font-weight:500; margin-bottom:0.5rem; }
        #result-box .value { font-size:3rem; font-weight:700; }
        #result-box.found   .value { color:#34d399; }
        #result-box.notfound .value { color:#f87171; }
        #result-box .context { color:var(--muted); font-size:0.9rem; margin-top:0.5rem; }
        
        .spinner { display:none; text-align:center; margin-top:1.5rem; color:var(--muted); font-size:0.95rem; }
        @keyframes spin { to { transform:rotate(360deg); } }
        .spin-icon { display:inline-block; animation:spin 0.8s linear infinite; margin-right:0.5rem; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>MobileMoney</h1>
        <div style="display:flex;gap:2rem;align-items:center;">
            <a href="<?= site_url('operateur') ?>">Dashboard</a>
            <a href="<?= site_url('bareme') ?>">Barèmes</a>
            <div style="font-weight:600;color:var(--muted);">TEST getFrais()</div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h2>Tester getFrais()</h2>
            <p>Saisissez un type d'opération et un montant pour voir les frais calculés.</p>
        </div>

        <div class="card">
            <div class="form-group">
                <label for="type_code">Type d'opération</label>
                <select id="type_code" class="form-control">
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($typesOperations as $type): ?>
                        <option value="<?= esc($type['code']) ?>">
                            <?= esc($type['libelle']) ?> (<?= esc($type['code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="montant">Montant (Ar)</label>
                <input type="number" id="montant" class="form-control" placeholder="Ex: 15000" min="1">
            </div>

            <button id="btn-calc" onclick="calculer()">Calculer les frais</button>

            <div class="spinner" id="spinner">
                <span class="spin-icon">⏳</span> Calcul en cours...
            </div>

            <div id="result-box">
                <div class="label" id="result-label"></div>
                <div class="value" id="result-value"></div>
                <div class="context" id="result-context"></div>
            </div>
        </div>
    </div>

    <script>
        async function calculer() {
            const typeCode = document.getElementById('type_code').value;
            const montant  = document.getElementById('montant').value;

            if (!typeCode || !montant) {
                alert('Veuillez sélectionner un type et saisir un montant.');
                return;
            }

            // Afficher le spinner, masquer l'ancien résultat
            const btn = document.getElementById('btn-calc');
            btn.disabled = true;
            document.getElementById('spinner').style.display = 'block';
            document.getElementById('result-box').style.display = 'none';

            try {
                const url = `<?= site_url('bareme/api/frais') ?>?type_code=${encodeURIComponent(typeCode)}&montant=${encodeURIComponent(montant)}`;
                const res  = await fetch(url);
                const data = await res.json();

                const frais = data.frais;
                const box   = document.getElementById('result-box');

                document.getElementById('result-label').textContent =
                    `getFrais('${typeCode}', ${parseInt(montant).toLocaleString('fr-FR')})`;

                if (frais > 0) {
                    box.className = 'found';
                    document.getElementById('result-value').textContent =
                        frais.toLocaleString('fr-FR') + ' Ar';
                    document.getElementById('result-context').textContent =
                        '✅ Tranche trouvée — frais à appliquer.';
                } else {
                    box.className = 'notfound';
                    document.getElementById('result-value').textContent = '0 Ar';
                    document.getElementById('result-context').textContent =
                        '⚠️ Aucune tranche active ne correspond à ce montant.';
                }

                box.style.display = 'block';
            } catch (e) {
                alert('Erreur lors de la communication avec le serveur.');
            } finally {
                document.getElementById('spinner').style.display = 'none';
                btn.disabled = false;
            }
        }

        // Permettre de valider avec Entrée
        document.getElementById('montant').addEventListener('keydown', e => {
            if (e.key === 'Enter') calculer();
        });
    </script>
</body>
</html>
