<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<main class="container-client">
    <header class="page-heading">
        <div class="eyebrow">Envoi groupé</div>
        <h1 class="page-title"><?= isset($resume) ? 'Confirmer l’envoi multiple' : 'Nouvel envoi multiple' ?></h1>
        <p class="page-subtitle">Le montant total est réparti équitablement entre les destinataires.</p>
    </header>

    <div class="row justify-content-center"><div class="col-lg-9"><section class="card"><div class="card-body">
        <?php if (session()->has('error')): ?><div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-2"></i><?= esc(session('error')) ?></div><?php endif; ?>
        <div class="d-flex justify-content-between p-3 mb-4 rounded-3 bg-light border"><span>Solde disponible</span><strong><?= number_format($solde, 0, ',', ' ') ?> Ar</strong></div>

        <?php if (!isset($resume)): ?>
            <?php
                $anciensNumeros = old('numeros');
                if (!is_array($anciensNumeros)) {
                    $anciensNumeros = preg_split('/[\r\n,;]+/', (string) $anciensNumeros) ?: [];
                }
                $anciensNumeros = array_values(array_filter(array_map('trim', $anciensNumeros)));
                while (count($anciensNumeros) < 2) {
                    $anciensNumeros[] = '';
                }
            ?>
            <form action="<?= base_url('/client/transfert-multiple') ?>" method="post">
                <?= csrf_field() ?><input type="hidden" name="action" value="previsualiser">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Numéros des destinataires</label>
                        <span class="recipient-counter"><span id="recipient-count"><?= count($anciensNumeros) ?></span>/20</span>
                    </div>
                    <div id="recipient-list" class="recipient-list">
                        <?php foreach ($anciensNumeros as $index => $numero): ?>
                            <div class="recipient-row">
                                <span class="recipient-number"><?= $index + 1 ?></span>
                                <input class="form-control recipient-input" type="tel" inputmode="numeric" name="numeros[]" value="<?= esc($numero) ?>" placeholder="Ex. 0331234567" pattern="[0-9 ]{10,14}" required>
                                <button class="btn btn-remove-recipient" type="button" aria-label="Retirer ce destinataire" title="Retirer"><i class="fa-solid fa-minus"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button id="add-recipient" class="btn btn-add-recipient mt-2" type="button"><i class="fa-solid fa-plus me-2"></i>Ajouter un destinataire</button>
                    <small class="form-text d-block mt-2">Minimum 2 et maximum 20 destinataires.</small>
                </div>
                <div class="mb-3"><label for="montant_total" class="form-label">Montant total à répartir</label><div class="input-group"><input class="form-control" type="number" id="montant_total" name="montant_total" min="200" step="1" value="<?= esc(old('montant_total')) ?>" required><span class="input-group-text bg-white">Ar</span></div></div>
                <div class="form-check p-3 mb-4 rounded-3 border bg-light"><input class="form-check-input ms-0 me-2" type="checkbox" value="1" id="inclure_frais_retrait" name="inclure_frais_retrait" <?= old('inclure_frais_retrait') ? 'checked' : '' ?>><label class="form-check-label" for="inclure_frais_retrait"><strong>Inclure les frais de retrait pour chaque destinataire</strong></label></div>
                <div class="d-flex flex-column-reverse flex-sm-row gap-2"><a href="<?= base_url('/client/dashboard') ?>" class="btn btn-outline-secondary px-4">Annuler</a><button class="btn btn-primary flex-grow-1" type="submit">Calculer et vérifier</button></div>
            </form>
        <?php else: ?>
            <div class="table-responsive mb-4"><table class="table align-middle"><thead><tr><th>Destinataire</th><th>Opérateur</th><th>Montant</th><th>Frais</th><th>Total</th></tr></thead><tbody>
                <?php foreach ($resume['envois'] as $envoi): ?><tr><td><strong><?= esc($envoi['telephone']) ?></strong></td><td><?= esc($envoi['operateur']['nom']) ?><br><small class="text-secondary"><?= $envoi['operateur']['est_interne'] ? 'Interne' : 'Externe' ?></small></td><td><?= number_format($envoi['calcul']['montant'], 0, ',', ' ') ?> Ar</td><td><?= number_format($envoi['calcul']['frais_total'], 0, ',', ' ') ?> Ar</td><td><strong><?= number_format($envoi['calcul']['total_debite'], 0, ',', ' ') ?> Ar</strong></td></tr><?php endforeach; ?>
            </tbody></table></div>
            <div class="p-3 mb-4 rounded-3 bg-light border"><div class="d-flex justify-content-between"><span>Montant total envoyé</span><strong><?= number_format($resume['montant_total_envoye'], 0, ',', ' ') ?> Ar</strong></div><div class="d-flex justify-content-between"><span>Total des frais</span><strong><?= number_format($resume['frais_total'], 0, ',', ' ') ?> Ar</strong></div><hr><div class="d-flex justify-content-between fs-5"><strong>Total débité</strong><strong class="text-primary"><?= number_format($resume['total_debite'], 0, ',', ' ') ?> Ar</strong></div></div>
            <form action="<?= base_url('/client/transfert-multiple') ?>" method="post">
                <?= csrf_field() ?><input type="hidden" name="action" value="confirmer"><?php foreach ($numeros_saisis as $numero): ?><input type="hidden" name="numeros[]" value="<?= esc($numero) ?>"><?php endforeach; ?><input type="hidden" name="montant_total" value="<?= (int) $resume['montant_total_envoye'] ?>"><input type="hidden" name="inclure_frais_retrait" value="<?= $resume['inclure_frais_retrait'] ? '1' : '0' ?>">
                <div class="d-flex flex-column-reverse flex-sm-row gap-2"><a href="<?= base_url('/client/transfert-multiple') ?>" class="btn btn-outline-secondary px-4">Modifier</a><button class="btn btn-primary flex-grow-1" type="submit"><i class="fa-solid fa-check me-2"></i>Confirmer tous les envois</button></div>
            </form>
        <?php endif; ?>
    </div></section></div></div>
</main>
<?php if (!isset($resume)): ?>
<script>
(() => {
    const list = document.getElementById('recipient-list');
    const addButton = document.getElementById('add-recipient');
    const counter = document.getElementById('recipient-count');
    const maximum = 20;

    const refresh = () => {
        const rows = [...list.querySelectorAll('.recipient-row')];
        rows.forEach((row, index) => {
            row.querySelector('.recipient-number').textContent = index + 1;
            row.querySelector('.btn-remove-recipient').disabled = rows.length <= 2;
        });
        counter.textContent = rows.length;
        addButton.disabled = rows.length >= maximum;
    };

    addButton.addEventListener('click', () => {
        if (list.children.length >= maximum) return;
        const row = document.createElement('div');
        row.className = 'recipient-row';
        row.innerHTML = '<span class="recipient-number"></span><input class="form-control recipient-input" type="tel" inputmode="numeric" name="numeros[]" placeholder="Ex. 0331234567" pattern="[0-9 ]{10,14}" required><button class="btn btn-remove-recipient" type="button" aria-label="Retirer ce destinataire" title="Retirer"><i class="fa-solid fa-minus"></i></button>';
        list.appendChild(row);
        refresh();
        row.querySelector('input').focus();
    });

    list.addEventListener('click', event => {
        const button = event.target.closest('.btn-remove-recipient');
        if (!button || list.children.length <= 2) return;
        button.closest('.recipient-row').remove();
        refresh();
    });

    refresh();
})();
</script>
<?php endif; ?>
<?= $this->endSection() ?>
