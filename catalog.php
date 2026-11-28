<?php
session_start();
require 'koneksi.php';

$genre_query = mysqli_query($conn, "SELECT * FROM Genre ORDER BY name ASC");

$search = trim($_GET['search'] ?? '');
$genre_filter = trim($_GET['genre'] ?? '');
$sort = trim($_GET['sort'] ?? 'title_asc');

$sql = "SELECT Films.*, Genre.name AS genre_name
        FROM Films
        JOIN Genre ON Films.genreId = Genre.id
        WHERE 1=1";

if ($search != '') {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND Films.title LIKE '%$safe_search%'";
}

if ($genre_filter != '') {
    $safe_genre = (int)$genre_filter;
    $sql .= " AND Films.genreId = $safe_genre";
}

$order_map = [
    'title_asc' => 'Films.title ASC',
    'title_desc' => 'Films.title DESC',
    'year_desc' => 'Films.releaseYear DESC',
    'year_asc' => 'Films.releaseYear ASC'
];

$sql .= " ORDER BY " . ($order_map[$sort] ?? 'Films.title ASC');
$result = mysqli_query($conn, $sql);

$poster = [
    1  => 'assets/Inception.jpg',       
    2  => 'assets/The Dark Knight.jpg', 
    3  => 'assets/Parasite.png',        
    4  => 'assets/Get Out.png',         
    5  => 'assets/Spirited Away.jpg',   
    6  => 'assets/Superbad.png',        
    7  => 'assets/Notebook.jpg',        
    8  => 'assets/Interstellarr.jpg',   
    9  => 'assets/Toy Story.jpg',       
    10 => 'assets/Mad Max.jpg',         
    11 => 'assets/Se7en.jpg'           
];
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Catalog - CineRent</title>
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
                <a href="catalog.php" class="nav-film active">Film Catalog</a>
                <?php if (strtolower($_SESSION['role'] ?? '') === 'admin'): ?>
                    <a href="admin.php" class="nav-film" style="color: #e50914; font-weight: bold;">Admin Dashboard</a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="kanan">
            <nav>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="my_rentals.php" class="nav-film" style="margin-right: 15px;">My Rentals</a>
                    <a href="cart.php" class="nav-film" style="margin-right: 15px;">Cart</a>
                    <a href="profile.php" class="nav-film" style="color: white; margin-right: 15px;">Halo, <?= htmlspecialchars($_SESSION['username']); ?></a>
                    <a href="logout.php" class="nav-register" style="background-color: #555;">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-login">Login</a>
                    <a href="register.php" class="nav-register">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <hr class="hr-nav">
    
    <section class="catalog-header">
        <h2>Film Catalog</h2>
        <p>Find your next movie night pick</p>
        
        <form action="catalog.php" method="GET" class="filter-bar">
            <input type="text" name="search" placeholder="Search movie title..." value="<?= htmlspecialchars($search) ?>">

            <select name="genre">
                <option value="">All Genres</option>
                <?php while ($g = mysqli_fetch_assoc($genre_query)): ?>
                    <option value="<?= $g['id'] ?>" <?= ($genre_filter == $g['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($g['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="sort">
                <option value="title_asc" <?= ($sort == 'title_asc') ? 'selected' : ''?>>Title (A-Z)</option>
                <option value="title_desc" <?= ($sort == 'title_desc') ? 'selected' : ''?>>Title (Z-A)</option>
                <option value="year_desc" <?= ($sort == 'year_desc') ? 'selected' : ''?>>Newest Year</option>
                <option value="year_asc" <?= ($sort == 'year_asc') ? 'selected' : ''?>>Oldest Year</option>
            </select>

            <button type="submit" class="btn-filter">Filter</button>
            <?php if ($search != '' || $genre_filter != ''): ?>
                <a href="catalog.php" class="btn-reset">Reset</a>
            <?php endif; ?>
        </form>
    </section>

    <section class="featured-card">
        <div class="main-card">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($film = mysqli_fetch_assoc($result)): ?>
                    <?php $img_src = !empty($film['poster']) ? $film['poster'] : ($poster[$film['id']] ?? 'assets/Inception.jpg'); ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($film['title']) ?>">
                        <span class="genre-card"><?= htmlspecialchars($film['genre_name']) ?></span>
                        <h3 class="title-card"><?= htmlspecialchars($film['title']) ?></h3>
                        <span><?= htmlspecialchars($film['releaseYear']) ?> &bull; <?= htmlspecialchars($film['director'] ?? '-') ?></span>

                        <?php if ($film['stock'] == 0): ?>
                            <span class="stock-status" style="color: red;">&#10006; Out of Stock</span>
                        <?php elseif ($film['stock'] <= 3): ?>
                            <span class="stock-status stock-low">&#9888; Low Stock (<?= $film['stock'] ?>)</span>
                        <?php else: ?>
                            <span class="stock-status stock-available">&#10003; Available (<?= $film['stock'] ?>)</span>
                        <?php endif; ?>

                        <div class="button-card">
                            <a href="film_detail.php?id=<?= $film['id'] ?>"><span>View Detail</span></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1 / -1; text-align: center; color: gray; padding: 40px;">
                    No movies found matching your search.
                </p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>