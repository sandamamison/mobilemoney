<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container container-client">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3"><i class="fas fa-arrow-right-arrow-left"></i> Effectuer un transfert</h4>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
                    <?php endif; ?>

                    <div class="alert alert-info">
                        <strong>Solde actuel :</strong> <?= number_format($solde, 0, ',', ' ') ?> Ar
                    </div>

                    <form action="<?= base_url('/client/transfert') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Numéro du destinataire</label>
                            <input type="text" class="form-control" id="telephone" name="telephone"
                                   value="<?= esc(old('telephone')) ?>" placeholder="Exemple : 0371234567" required>
                        </div>

                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant à transférer</label>
                            <input type="number" class="form-control" id="montant" name="montant"
                                   value="<?= esc(old('montant')) ?>" min="100" step="1" required>
                            <small class="form-text text-muted">Les frais sont calculés automatiquement.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary">Valider le transfert</button>
                            <a href="<?= base_url('/client/dashboard') ?>" class="btn btn-outline-secondary">Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
