<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container container-client">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3"><i class="fas fa-money-bill-transfer"></i> Effectuer un retrait</h4>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle"></i> <?= session('success') ?>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info" role="alert">
                        <strong>Solde actuel :</strong> <?= number_format($solde, 0, ',', ' ') ?> Ar
                    </div>

                    <form action="<?= base_url('/client/retrait') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant à retirer</label>
                            <input type="number" class="form-control" id="montant" name="montant" min="100" step="100" placeholder="Ex: 5000" required>
                            <small class="form-text text-muted">Les frais seront calculés automatiquement selon le barème.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-check"></i> Valider le retrait
                            </button>
                            <a href="<?= base_url('/client/dashboard') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
