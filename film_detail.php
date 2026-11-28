<?php
session_start();
require 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT Films.*, Genre.name AS genre_name
        FROM Films
        JOIN Genre ON Films.genreId = Genre.id
        WHERE Films.id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: catalog.php");
    exit;
}

$film = mysqli_fetch_assoc($result);

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
$img_src = !empty($film['poster']) ? $film['poster'] : ($poster[$film['id']] ?? 'assets/Inception.jpg');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=  htmlspecialchars($film['title']) ?> - CineRent</title>
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

    <main class="detail-wrapper">
        <a href="catalog.php" class="back-link">&larr; Back to Catalog</a>
        
        <div class="detail-card">
            <div class="detail-poster">
                <img src="<?=  htmlspecialchars($img_src) ?>" alt="<?=  htmlspecialchars($film['title']) ?>">
            </div>
            <div class="detail-info">
                <span class="genre-card"><?=  htmlspecialchars($film['genre_name']) ?></span>
                <h1><?= htmlspecialchars($film['title']) ?></h1>
                
                <div class="detail-meta">
                    <span><strong>Release Year:</strong> <?=  htmlspecialchars($film['releaseYear'] ?? '-') ?></span>
                    <span><strong>Director:</strong> <?= htmlspecialchars($film['director'] ?? '-') ?></span>
                </div>
                
                <p class="detail-desc"><?= htmlspecialchars($film['description']) ?></p>
                
                <?php if ($film['stock'] == 0): ?>
                    <span class="stock-status" style="color: red;">&#10006; Out of Stock</span>
                <?php elseif ($film['stock'] <= 3): ?>
                    <span class="stock-status stock-low">&#9888; Low Stock (<?=  $film['stock'] ?> copies left)</span>
                <?php else: ?>
                    <span class="stock-status stock-available">&#10003; Available (<?=  $film['stock'] ?> copies left)</span>
                <?php endif; ?>

                <div class="detail-actions">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="login.php" class="btn-rent">Login to Rent</a>
                    <?php elseif ($film['stock'] > 0): ?>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="film_id" value="<?=  $film['id'] ?>">
                            <button type="submit" name="add_to_cart" class="btn-rent">+ Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <button class="btn-rent btn-disabled" disabled>Currently Unavailable</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>