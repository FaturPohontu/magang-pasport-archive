
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Arsip Paspor</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow-sm border-0 rounded-4 w-100"
         style="max-width: 420px;">
        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">
                <h3 class="fw-bold">Arsip Paspor</h3>
                <p class="text-secondary mb-0">
                    Silakan login untuk melanjutkan
                </p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-warning">
                    <ul class="mb-0">
                        <?php foreach (
                            session()->getFlashdata('errors') as $error
                        ): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        value="<?= esc(old('username') ?? '') ?>"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        Password
                    </label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>
            </form>

            <p class="text-center text-secondary small mt-4 mb-0">
                Sistem Informasi Pengarsipan Data Paspor
            </p>
        </div>
    </div>
</div>

</body>
</html>
