<?php 
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_film'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genreId = (int)$_POST['genreId'];
    $releaseYear = (int)$_POST['releaseYear'];
    $stock = (int)$_POST['stock'];
    $director = mysqli_real_escape_string($conn, $_POST['director']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $poster_path = '';
    if (!empty($_FILES['poster']['name'])) {
        $file_name = time() . '_' . basename($_FILES['poster']['name']);
        $target = 'assets/' . $file_name;
        if (move_uploaded_file($_FILES['poster']['tmp_name'], $target)) {
            $poster_path = mysqli_real_escape_string($conn, $target);
        }
    }

    $sql_add = "INSERT INTO Films (title, genreId, releaseYear, stock, director, description, poster) 
                VALUES ('$title', $genreId, $releaseYear, $stock, '$director', '$description', '$poster_path')";

    if (mysqli_query($conn, $sql_add)) {
        $msg = "Film '$title' added successfully!";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock'])) {
    $film_id = (int)$_POST['film_id'];
    $new_stock = (int)$_POST['stock'];
    mysqli_query($conn, "UPDATE Films SET stock = $new_stock WHERE id = $film_id");
    $msg = "Stock updated!";
}

if (isset($_GET['delete_film'])) {
    $film_id = (int)$_GET['delete_film'];
    mysqli_query($conn, "DELETE FROM Films WHERE id = $film_id");
    header("Location: admin.php");
    exit;
}

$genres = mysqli_query($conn, "SELECT * FROM Genre ORDER BY name ASC");
$films = mysqli_query($conn, "SELECT Films.*, Genre.name AS genre_name
FROM Films
JOIN Genre ON Films.genreId = Genre.id
ORDER BY Films.id DESC");

$all_rentals = mysqli_query($conn, "SELECT Rentals.*, Users.username
FROM Rentals
JOIN Users ON Rentals.userId = Users.id
ORDER BY Rentals.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CineRent</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="kiri">
            <div class="logo">
                <a href="index.php" style="text-decoration: none; color: white;">
                    <h1>CineRent</h1>
                </a>
            </div>
            <nav>
                <a href="index.php" class="nav-home">Home</a>
                <a href="catalog.php" class="nav-film">Film Catalog</a>
                <a href="admin.php" class="nav-film active" style="color: white; font-weight: bold;">Admin Dashboard</a>
            </nav>
        </div>

        <div class="kanan">
            <nav>
                <span style="color: white; margin-right: 15px;">Admin: <?= htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="nav-register" style="background-color: #555;">Logout</a>
            </nav>
        </div>
    </header>
    <hr class="hr-nav">

    <main class="admin-wrapper">
        <h2>Admin Dashboard</h2>

        <?php if ($msg != ''): ?>
            <div class="cart-alert"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <section class="admin-section">
            <h3>Add New Film</h3>
            <form action="admin.php" method="POST" enctype="multipart/form-data" class="admin-form">
                <input type="text" name="title" placeholder="Film Title" required>
                <select name="genreId" required>
                    <option value="">-- Select Genre --</option>
                    <?php while ($g = mysqli_fetch_assoc($genres)): ?>
                        <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="releaseYear" placeholder="Year (e.g. 2024)" required>
                <input type="number" name="stock" placeholder="Stock" min="0" required>
                <input type="text" name="director" placeholder="Director (max 50 chars)" maxlength="50" required style="grid-column: 1 / 3;">
                <input type="file" name="poster" accept="image/*" style="grid-column: 3 / -1;">
                <textarea name="description" placeholder="Film description..." rows="2" required style="grid-column: 1 / -1; background: #1f1f1f; border: 1px solid var(--border); color: white; padding: 10px 12px; border-radius: 6px; font-family: inherit;"></textarea>
                <button type="submit" name="add_film" class="btn-rent" style="grid-column: 1 / -1; justify-self: start;">+ Add Film</button>
            </form>
        </section>

        <section class="admin-section">
            <h3>Manage Films</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Year</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($f = mysqli_fetch_assoc($films)): ?>
                        <tr>
                            <td>#<?= $f['id'] ?></td>
                            <td><?= htmlspecialchars($f['title']) ?></td>
                            <td><?= htmlspecialchars($f['genre_name']) ?></td>
                            <td><?= htmlspecialchars($f['releaseYear']) ?></td>
                            <td>
                                <form action="admin.php" method="POST" style="display: flex; gap: 6px; align-items: center;">
                                    <input type="hidden" name="film_id" value="<?= $f['id'] ?>">
                                    <input type="number" name="stock" value="<?= $f['stock'] ?>" min="0" class="stock-input">
                                    <button type="submit" name="update_stock" class="btn-update">Save</button>
                                </form>
                            </td>
                            <td>
                                <a href="admin.php?delete_film=<?= $f['id'] ?>" class="btn-remove" onclick="return confirm('Delete this film?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section class="admin-section">
            <h3>All Member Rentals</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Rental ID</th>
                        <th>Member</th>
                        <th>Borrowed</th>
                        <th>Due Date</th>
                        <th>Returned</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($r = mysqli_fetch_assoc($all_rentals)): ?>
                        <tr>
                            <td>#<?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['username']) ?></td>
                            <td><?= htmlspecialchars($r['borrowDate']) ?></td>
                            <td><?= htmlspecialchars($r['dueDate']) ?></td>
                            <td><?= $r['returnDate'] ? htmlspecialchars($r['returnDate']) : '-' ?></td>
                            <td>
                                <span class="status-badge <?= ($r['status'] == 'Active') ? 'badge-active' : 'badge-returned' ?>">
                                    <?= htmlspecialchars($r['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>