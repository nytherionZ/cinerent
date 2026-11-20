<?php
session_start();
require 'koneksi.php';

$featured_sql = "SELECT Films.*, Genre.name AS genre_name
FROM Films
JOIN Genre ON Films.genreId = Genre.id
ORDER BY Films.id ASC
LIMIT 4";

$featured_result = mysqli_query($conn, $featured_sql);

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
    <title>Cinerent</title>
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
                <a href="index.php" class="nav-home active">Home</a>
                <a href="catalog.php" class="nav-film">Film Catalog</a>
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
                    <span style="color: white; margin-right: 15px;">Halo, <?= htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="nav-register" style="background-color: #555;">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-login">Login</a>
                    <a href="register.php" class="nav-register">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <hr class="hr-nav">

    <section class="main-welcome">
        <div class="welcome">
            <h1>Welcome to <span class="welcome-cine">CineRent</span></h1>
        </div>

        <div class="description">
            <span>Browse thousands of DVD & Blu-ray titles — rent, enjoy, return.</span>
        </div>

        <div class="welcome-btn">
            <div class="browse-btn">
                <a href="catalog.php" class="browse-btn">
                    <span>Browse Catalog</span>
                </a>
            </div>

            <div class="join-btn">
                <a href="register.php" class="join-btn-a">
                    <span>Join Free</span>
                </a>
            </div>
        </div>
    </section>
    <hr class="hr-welcome">

    <section class="featured">
        <div class="featured-red">
            <span>FEATURED TONIGHT</span>
        </div>

        <div class="randomly-picked">
            <h2>Randomly Picked For You</h2>
        </div>        
        <hr class="hr-featured">
    </section>

    <section class="featured-card">
        <div class="main-card">
            <?php while ($film = mysqli_fetch_assoc($featured_result)): ?>
                <?php $img_src = !empty($film['poster']) ? $film['poster'] : ($poster[$film['id']] ?? 'assets/Inception.jpg'); ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($film['title']) ?>">
                    <span class="genre-card"><?= htmlspecialchars($film['genre_name']) ?></span>
                    <h3 class="title-card"><?= htmlspecialchars($film['title']) ?></h3>
                    <span><?= htmlspecialchars($film['releaseYear']) ?></span>

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
        </div>
        <a href="catalog.php" class="full-cat-home">View Full Catalog</a>
    </section>
    
    <footer>
        <div class="footer-top">
            <div class="footer-bot">
                <h2 class="footer-title">CineRent</h2>
                <p>Your premier destination for DVD & Blu-ray rentals. Thousands of titles, one platform.</p>
            </div>
            
            <div class="footer-bot">
                <h3 class="footer-title">QUICK LINKS</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="catalog.php">Film Catalog</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </div>
            
            <div class="footer-bot">
                <h3 class="footer-title">CONTACT US</h3>
                <p>support@cinerent.com<br>
                +62 21 1234-5678<br>
                Jakarta, Indonesia<br>
            </p>
        </div>
    </div>
    <hr class="hr-foot">
    <div class="footer-down">
        &copy; 2026 CineRent — All rights reserved.
    </div>
</footer>
</body>
</html>