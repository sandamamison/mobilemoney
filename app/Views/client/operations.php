<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<main class="container-client">
    <header class="page-heading">
        <div class="eyebrow">Suivi du compte</div>
        <h1 class="page-title">Historique des opérations</h1>
        <p class="page-subtitle">Consultez les montants, les opérateurs et le détail des frais.</p>
    </header>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="fas fa-list"></i> Toutes les opérations</h4>
                <a href="<?= base_url('/client/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (!empty($operations)) : ?>
                <div class="history-list">
                    <?php foreach ($operations as $groupe) : ?>
                        <article class="history-item">
                            <div class="history-summary">
                                <div><div class="fw-bold"><?= $groupe['est_groupe'] ? '<i class="fa-solid fa-users me-1"></i> Envoi multiple' : esc($groupe['libelle']) ?></div><div class="text-muted small"><?= $groupe['est_groupe'] ? $groupe['nombre_operations'] . ' destinataires · Groupe : ' . esc($groupe['groupe_reference']) : 'Réf. : ' . esc($groupe['reference']) ?></div><div class="text-muted small"><?= esc($groupe['date_operation']) ?></div></div>
                                <div class="text-end"><div class="fw-bold <?= $groupe['est_sortante'] ? 'amount-out' : 'amount-in' ?>"><?= $groupe['est_sortante'] ? '− ' : '+ ' ?><?= number_format($groupe['montant_total'] + ($groupe['est_sortante'] ? $groupe['frais_total'] : 0), 0, ',', ' ') ?> Ar</div><?php if ($groupe['frais_total'] > 0): ?><small class="text-muted">Montant <?= number_format($groupe['montant_total'], 0, ',', ' ') ?> + frais <?= number_format($groupe['frais_total'], 0, ',', ' ') ?> Ar</small><?php endif; ?></div>
                            </div>
                            <?php if (strtoupper((string) $groupe['libelle']) === 'TRANSFERT' || $groupe['est_groupe']): ?>
                                <div class="transfer-details">
                                    <?php foreach ($groupe['operations'] as $operation): ?>
                                        <div class="transfer-detail-row">
                                            <div><strong><?= esc($operation['destinataire_telephone'] ?: 'Réception') ?></strong><span class="operator-badge"><?= esc($operation['operateur_destination'] ?: 'MobiCash') ?><?= !empty($operation['transfert_externe']) ? ' · Externe' : ' · Interne' ?></span></div>
                                            <div class="text-end"><strong><?= number_format((int) $operation['montant'], 0, ',', ' ') ?> Ar</strong><small>Transfert <?= number_format((int) ($operation['frais_transfert'] ?? 0), 0, ',', ' ') ?> · Commission <?= number_format((int) ($operation['commission_externe'] ?? 0), 0, ',', ' ') ?> · Retrait inclus <?= number_format((int) ($operation['frais_retrait_inclus'] ?? 0), 0, ',', ' ') ?> Ar</small></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="alert alert-light text-center mb-0" role="alert">
                    <i class="fas fa-info-circle"></i> Aucune opération enregistrée pour le moment.
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
