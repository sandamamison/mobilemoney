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
            <form action="<?= base_url('/client/transfert-multiple') ?>" method="post">
                <?= csrf_field() ?><input type="hidden" name="action" value="previsualiser">
                <div class="mb-3">
                    <label for="numeros" class="form-label">Numéros des destinataires</label>
                    <textarea class="form-control" id="numeros" name="numeros" rows="6" placeholder="0331234560&#10;0371234567" required><?= esc(old('numeros')) ?></textarea>
                    <small class="form-text">Un numéro par ligne, ou séparés par une virgule. Maximum : 20.</small>
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
                <?= csrf_field() ?><input type="hidden" name="action" value="confirmer"><input type="hidden" name="numeros" value="<?= esc($numeros_saisis) ?>"><input type="hidden" name="montant_total" value="<?= (int) $resume['montant_total_envoye'] ?>"><input type="hidden" name="inclure_frais_retrait" value="<?= $resume['inclure_frais_retrait'] ? '1' : '0' ?>">
                <div class="d-flex flex-column-reverse flex-sm-row gap-2"><a href="<?= base_url('/client/transfert-multiple') ?>" class="btn btn-outline-secondary px-4">Modifier</a><button class="btn btn-primary flex-grow-1" type="submit"><i class="fa-solid fa-check me-2"></i>Confirmer tous les envois</button></div>
            </form>
        <?php endif; ?>
    </div></section></div></div>
</main>
<?= $this->endSection() ?>
