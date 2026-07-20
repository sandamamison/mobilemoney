<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<main class="container-client">
    <header class="page-heading">
        <div class="eyebrow">Vue d’ensemble</div>
        <h1 class="page-title">Bonjour<?= !empty($client['nom']) ? ', ' . esc($client['nom']) : '' ?></h1>
        <p class="page-subtitle">Gérez votre argent et consultez vos dernières opérations.</p>
    </header>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card balance-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-wallet"></i> Solde disponible</h5>
                        <span class="badge bg-light text-dark">Compte #<?= esc($compte['id'] ?? '') ?></span>
                    </div>
                    <div class="balance-display"><?= number_format($solde, 0, ',', ' ') ?> <small class="fs-6">Ar</small></div>
                    <p class="mb-0">
                        <i class="fas fa-phone"></i> <?= esc($client['telephone'] ?? '') ?><br>
                        <small><?= esc($client['nom'] ?? 'Client') ?></small>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3"><i class="fas fa-home"></i> Bienvenue</h4>
                    <p class="text-muted mb-4">
                        Gérez votre portefeuille, consultez votre solde et suivez vos dernières opérations en toute simplicité.
                    </p>

                    <div class="quick-actions">
                        <a href="<?= base_url('/client/depot') ?>" class="btn btn-success">
                            <i class="fas fa-arrow-down"></i> Dépôt
                        </a>
                        <a href="<?= base_url('/client/retrait') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-up"></i> Retrait
                        </a>
                        <a href="<?= base_url('/client/transfert') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-right-arrow-left"></i> Transfert
                        </a>
                        <a href="<?= base_url('/client/operations') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Voir l'historique
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Dernières opérations</h5>
                        <a href="<?= base_url('/client/operations') ?>" class="btn btn-sm btn-outline-secondary">
                            Voir tout
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
                            <i class="fas fa-info-circle"></i> Aucune opération récente pour le moment.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
