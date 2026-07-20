<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<main class="auth-page">
    <div class="auth-wrap">
        <section class="auth-card">
            <div class="card-body">
                <div class="auth-brand"><span class="brand-mark"><i class="fa-solid fa-wallet"></i></span><span>MobiCash</span></div>
                <div class="eyebrow">Espace client</div>
                <h1 class="page-title">Bienvenue</h1>
                <p class="page-subtitle mb-4">Accédez à votre compte avec votre numéro mobile.</p>

                <?php if (session()->has('error')): ?><div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-2"></i><?= esc(session('error')) ?></div><?php endif; ?>
                <?php if (session()->has('success')): ?><div class="alert alert-success"><i class="fa-solid fa-circle-check me-2"></i><?= esc(session('success')) ?></div><?php endif; ?>

                <form action="<?= base_url('connexion') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label for="telephone" class="form-label">Numéro de téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-phone text-secondary"></i></span>
                            <input type="tel" class="form-control border-start-0" id="telephone" name="telephone" value="<?= esc(old('telephone')) ?>" placeholder="033 12 345 67" inputmode="numeric" autocomplete="tel" required autofocus>
                        </div>
                        <div class="form-text mt-2">Préfixes acceptés : 033 et 037 · 10 chiffres</div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">Continuer <i class="fa-solid fa-arrow-right ms-2"></i></button>
                </form>

                <div class="security-note"><i class="fa-solid fa-bolt text-primary mt-1"></i><span>Aucune inscription préalable. Votre compte est créé automatiquement à la première connexion.</span></div>
            </div>
        </section>
        <p class="text-center text-white-50 small mt-4 mb-0">Mobile Money simple, rapide et sécurisé</p>
    </div>
</main>
<?= $this->endSection() ?>
