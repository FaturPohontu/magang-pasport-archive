
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Arsip Paspor</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand fw-bold">Arsip Paspor</span>
        <a href="<?= site_url('logout') ?>"
           class="btn btn-outline-light btn-sm">
            Logout
        </a>
    </div>
</nav>

<main class="container py-5">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h2 class="fw-bold">
                Selamat datang, <?= esc(session()->get('nama')) ?>!
            </h2>
            <p class="text-secondary">
                Anda berhasil masuk ke sistem pengarsipan data paspor.
            </p>

            <hr>

            <h5>Menu Utama</h5>
            <p class="mb-0">
                Fitur scan resi paspor akan kita bangun pada tahap berikutnya.
            </p>
        </div>
    </div>
</main>

</body>
</html>
