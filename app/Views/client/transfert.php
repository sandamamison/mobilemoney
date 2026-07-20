<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<main class="container-client">
    <header class="page-heading">
        <div class="eyebrow">Envoi d’argent</div>
        <h1 class="page-title"><?= isset($resume) ? 'Confirmer le transfert' : 'Nouveau transfert' ?></h1>
        <p class="page-subtitle">Vérifiez toujours le numéro et le total avant de confirmer.</p>
    </header>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <section class="card">
                <div class="card-body">
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-2"></i><?= esc(session('error')) ?></div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center p-3 mb-4 rounded-3 bg-light border">
                        <span class="text-secondary">Solde disponible</span>
                        <strong><?= number_format($solde, 0, ',', ' ') ?> Ar</strong>
                    </div>

                    <?php if (!isset($resume)): ?>
                        <form action="<?= base_url('/client/transfert') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="previsualiser">

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Numéro du destinataire</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone"
                                       value="<?= esc(old('telephone')) ?>" placeholder="033 ou 037..."
                                       inputmode="numeric" required>
                            </div>

                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant à transférer</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="montant" name="montant"
                                           value="<?= esc(old('montant')) ?>" min="100" step="1" required>
                                    <span class="input-group-text bg-white">Ar</span>
                                </div>
                            </div>

                            <div class="form-check p-3 mb-4 rounded-3 border bg-light">
                                <input class="form-check-input ms-0 me-2" type="checkbox" value="1"
                                       id="inclure_frais_retrait" name="inclure_frais_retrait"
                                       <?= old('inclure_frais_retrait') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inclure_frais_retrait">
                                    <strong>Inclure les frais de retrait</strong><br>
                                    <small class="text-secondary">Le destinataire recevra le montant prévu pour son retrait.</small>
                                </label>
                            </div>

                            <div class="d-flex flex-column-reverse flex-sm-row gap-2">
                                <a href="<?= base_url('/client/dashboard') ?>" class="btn btn-outline-secondary px-4">Annuler</a>
                                <button type="submit" class="btn btn-primary flex-grow-1">Afficher le résumé</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <?php $operateur = $resume['operateur']; $calcul = $resume['calcul']; ?>
                        <div class="p-3 mb-3 rounded-3 border">
                            <div class="d-flex justify-content-between mb-2"><span class="text-secondary">Destinataire</span><strong><?= esc($resume['telephone']) ?></strong></div>
                            <div class="d-flex justify-content-between"><span class="text-secondary">Opérateur</span><strong><?= esc($operateur['nom']) ?> · <?= $operateur['est_interne'] ? 'Interne' : 'Externe' ?></strong></div>
                        </div>
                        <div class="p-3 mb-4 rounded-3 border bg-light">
                            <div class="d-flex justify-content-between py-1"><span>Montant envoyé</span><strong><?= number_format($calcul['montant'], 0, ',', ' ') ?> Ar</strong></div>
                            <div class="d-flex justify-content-between py-1"><span>Frais de transfert</span><span><?= number_format($calcul['frais_transfert'], 0, ',', ' ') ?> Ar</span></div>
                            <?php if ($calcul['commission_externe'] > 0): ?><div class="d-flex justify-content-between py-1"><span>Commission autre opérateur</span><span><?= number_format($calcul['commission_externe'], 0, ',', ' ') ?> Ar</span></div><?php endif; ?>
                            <?php if ($calcul['frais_retrait'] > 0): ?><div class="d-flex justify-content-between py-1"><span>Frais de retrait inclus</span><span><?= number_format($calcul['frais_retrait'], 0, ',', ' ') ?> Ar</span></div><?php endif; ?>
                            <hr>
                            <div class="d-flex justify-content-between fs-5"><strong>Total débité</strong><strong class="text-primary"><?= number_format($calcul['total_debite'], 0, ',', ' ') ?> Ar</strong></div>
                        </div>

                        <form action="<?= base_url('/client/transfert') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="confirmer">
                            <input type="hidden" name="telephone" value="<?= esc($resume['telephone']) ?>">
                            <input type="hidden" name="montant" value="<?= (int) $calcul['montant'] ?>">
                            <input type="hidden" name="inclure_frais_retrait" value="<?= $resume['inclure_frais_retrait'] ? '1' : '0' ?>">
                            <div class="d-flex flex-column-reverse flex-sm-row gap-2">
                                <a href="<?= base_url('/client/transfert') ?>" class="btn btn-outline-secondary px-4">Modifier</a>
                                <button type="submit" class="btn btn-primary flex-grow-1"><i class="fa-solid fa-check me-2"></i>Confirmer le transfert</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
