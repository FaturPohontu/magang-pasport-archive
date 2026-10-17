<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna</title>
</head>
<body>
    <h1>Daftar Pengguna</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
        </tr>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= $user['username']; ?></td>
                <td><?= $user['nama']; ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Belum ada data pengguna.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>