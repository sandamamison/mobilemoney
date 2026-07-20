<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container container-client">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="fas fa-list"></i> Historique des opérations</h4>
                <a href="<?= base_url('/client/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (!empty($operations)) : ?>
                <div class="list-group">
                    <?php foreach ($operations as $operation) : ?>
                        <div class="list-group-item d-flex justify-content-between align-items-start operation-card">
                            <div>
                                <div class="fw-bold">
                                    <?= esc($operation['libelle'] ?? 'Opération') ?>
                                </div>
                                <div class="text-muted small">
                                    Ref: <?= esc($operation['reference'] ?? '') ?>
                                </div>
                                <div class="text-muted small">
                                    <?= esc($operation['date_operation'] ?? '') ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold <?= ($operation['compte_source_id'] == session()->get('compte_id') ? 'text-danger' : 'text-success') ?>">
                                    <?= ($operation['compte_source_id'] == session()->get('compte_id') ? '− ' : '+ ') ?><?= number_format((int) ($operation['montant'] ?? 0), 0, ',', ' ') ?> Ar
                                </div>
                                <small class="text-muted">Statut: <?= esc($operation['statut'] ?? '') ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="alert alert-light text-center mb-0" role="alert">
                    <i class="fas fa-info-circle"></i> Aucune opération enregistrée pour le moment.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
