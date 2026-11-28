<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return_rental'])) {
    $rental_id = (int)$_POST['rental_id'];
    $cek = mysqli_query($conn, "SELECT * FROM Rentals WHERE id = $rental_id AND userId = $user_id AND status = 'Active'");

    if (mysqli_num_rows($cek) == 1) {
        $today = date('Y-m-d');
        mysqli_query($conn, "UPDATE Rentals SET status = 'Returned', returnDate = '$today' WHERE id = $rental_id");

        $details = mysqli_query($conn, "SELECT filmId FROM RentalDetails WHERE rentalId = $rental_id");
        while ($d = mysqli_fetch_assoc($details)) {
            $f_id = (int)$d['filmId'];
            mysqli_query($conn, "UPDATE Films SET stock = stock + 1 WHERE id = $f_id");
        }
        $msg = "Films returned successfully! Thank you for renting with CineRent.";
    }
}

$rentals = mysqli_query($conn, "SELECT * FROM Rentals WHERE userId = $user_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rentals - CineRent</title>
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
                    <a href="my_rentals.php" class="nav-film active" style="margin-right: 15px; color: white; font-weight: bold;" >My Rentals</a>
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

    <main class="cart-wrapper">
        <h2>My Rentals</h2>
        <?php if ($msg != ''): ?>
            <div class="cart-alert"><?=  htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($rentals) > 0): ?>
            <div class="rental-list">
                <?php while ($r = mysqli_fetch_assoc($rentals)): ?>
                    <?php 
                        $r_id = (int)$r['id'];
                        $films_q = mysqli_query($conn, "SELECT Films.title, Films.releaseYear
                        FROM RentalDetails
                        JOIN Films ON RentalDetails.filmId = Films.id
                        WHERE RentalDetails.rentalId = $r_id"); 
                    ?>
                    <div class="rental-card">
                        <div class="rental-top">
                            <div>
                                <h3>Rental #<?= $r['id'] ?></h3>
                                <p class="rental-dates">
                                    Borrowed: <strong><?= htmlspecialchars($r['borrowDate']) ?></strong> &bull;
                                    Due: <strong><?=  htmlspecialchars($r['dueDate']) ?></strong>
                                    <?php if ($r['returnDate']): ?>
                                        &bull; Returned: <strong><?= htmlspecialchars($r['returnDate']) ?></strong>
                                    <?php endif; ?>
                                </p>
                            </div>

                                <div>
                                    <span class="status-badge <?= ($r['status'] == 'Active') ? 'badge-active' : 'badge-returned' ?>"><?=  htmlspecialchars($r['status']) ?></span>
                                </div>
                                    </div>
                                <ul class="rental-films">
                                    <?php while ($f = mysqli_fetch_assoc($films_q)): ?>
                                        <li><?=  htmlspecialchars($f['title']) ?> (<?= htmlspecialchars($f['releaseYear']) ?>)</li>
                                    <?php endwhile; ?>
                                </ul>

                                <?php if ($r['status'] == 'Active'): ?>
                                    <form action="my_rentals.php" method="POST" style="margin-top: 15px;">
                                        <input type="hidden" name="rental_id" value="<?=  $r['id'] ?>">
                                        <button type="submit" name="return_rental" class="btn-return">Return Films</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="cart-empty">
                        <p>You haven't rented any films yet.</p>
                        <a href="catalog.php" class="btn-rent" style="margin-top: 15px;">Browse Catalog</a>
                    </div>
                <?php endif; ?>
    </main>
</body>
</html>