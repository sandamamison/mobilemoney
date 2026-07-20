<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container container-client">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-3"><i class="fas fa-money-bill-transfer"></i> Retrait</h4>
            <p class="text-muted">Cette fonctionnalité sera bientôt disponible.</p>
            <a href="<?= base_url('/client/dashboard') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
