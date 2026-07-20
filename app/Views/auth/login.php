<?= $this->extend('layouts/app') ?>

<?php $validation = $validation ?? service('validation'); ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">
                        <i class="fas fa-mobile-alt"></i> Mobile Money
                    </h2>
                    
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?= session('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('connexion') ?>" method="post" id="loginForm">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="telephone" class="form-label">
                                <i class="fas fa-phone"></i> Numéro de téléphone
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg <?= $validation->hasError('telephone') ? 'is-invalid' : '' ?>" 
                                id="telephone" 
                                name="telephone" 
                                placeholder="033 12 345 67 ou 037 12 345 67" 
                                value="<?= old('telephone') ?>"
                                required
                                autocomplete="off"
                            >
                            <?php if ($validation->hasError('telephone')): ?>
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-times-circle"></i> <?= $validation->getError('telephone') ?>
                                </div>
                            <?php endif; ?>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Exemple : 0331234567 — Préfixes acceptés : 033 et 037
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Se connecter
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <div class="text-center text-muted small">
                        <p><i class="fas fa-bolt"></i> Connexion automatique</p>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-info mt-4" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-lightbulb"></i> Besoin d'aide?
                </h6>
                <small>
                    Saisissez simplement votre numéro de téléphone. Votre espace client sera créé automatiquement s'il n'existe pas encore.
                </small>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-weight: 600;
        transition: transform 0.2s;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
</style>
<?= $this->endSection() ?>
