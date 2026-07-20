<?php $adminSection = service('uri')->getSegment(1); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<header class="navbar admin-header">
    <a class="admin-brand" href="<?= site_url('operateur') ?>">
        <span class="admin-brand-mark"><i class="fa-solid fa-chart-line"></i></span>
        <span>MobiCash <small>Opérateur</small></span>
    </a>
    <nav class="admin-navigation" aria-label="Navigation opérateur">
        <a class="<?= $adminSection === 'operateur' ? 'active' : '' ?>" href="<?= site_url('operateur') ?>"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
        <a class="<?= $adminSection === 'comptes' ? 'active' : '' ?>" href="<?= site_url('comptes') ?>"><i class="fa-solid fa-wallet"></i> Comptes</a>
        <a class="<?= $adminSection === 'prefixe' ? 'active' : '' ?>" href="<?= site_url('prefixe') ?>"><i class="fa-solid fa-phone"></i> Préfixes</a>
        <a class="<?= $adminSection === 'autres-operateurs' ? 'active' : '' ?>" href="<?= site_url('autres-operateurs') ?>"><i class="fa-solid fa-tower-broadcast"></i> Autres opérateurs</a>
        <a class="<?= $adminSection === 'typesoperation' ? 'active' : '' ?>" href="<?= site_url('typesoperation') ?>"><i class="fa-solid fa-list-check"></i> Types</a>
        <a class="<?= $adminSection === 'bareme' ? 'active' : '' ?>" href="<?= site_url('bareme') ?>"><i class="fa-solid fa-sliders"></i> Barèmes</a>
        <a class="<?= $adminSection === 'gains' && service('uri')->getSegment(2) === '' ? 'active' : '' ?>" href="<?= site_url('gains') ?>"><i class="fa-solid fa-chart-column"></i> Gains</a>
        <a class="<?= $adminSection === 'gains' && service('uri')->getSegment(2) === 'historique' ? 'active' : '' ?>" href="<?= site_url('gains/historique') ?>"><i class="fa-solid fa-clock-rotate-left"></i> Historique</a>
        <a class="admin-client-link" href="<?= site_url('login') ?>"><i class="fa-solid fa-user"></i> Client</a>
    </nav>
</header>
