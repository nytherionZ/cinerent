<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $film_id = (int)$_POST['film_id'];
    $cek = mysqli_query($conn, "SELECT * FROM CartItems WHERE userId = $user_id AND filmId = $film_id");
    if (mysqli_num_rows($cek) == 0) {
        $today = date('Y-m-d');
        mysqli_query($conn, "INSERT INTO CartItems (filmId, userId, addedDate) VALUES ($film_id, $user_id, '$today')");
        $msg = "Film added to your cart!";
    }

    else {
        $msg = "Film is already in your cart.";
    }
}

if (isset($_GET['remove'])) {
    $film_id= (int)$_GET['remove'];
    mysqli_query($conn, "DELETE FROM CartItems WHERE userId = $user_id AND filmId = $film_id");
    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $cart_check = mysqli_query($conn, "SELECT filmId FROM CartItems WHERE userId = $user_id");

    if (mysqli_num_rows($cart_check) > 0) {
        $borrowDate = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime('+7 days'));

        $insert_rental = "INSERT INTO Rentals (userId, borrowDate, dueDate, returnDate, status) VALUES ($user_id, '$borrowDate', '$dueDate', NULL, 'Active')";

        if (mysqli_query($conn, $insert_rental)) {
            $rental_id = mysqli_insert_id($conn);

            while ($item = mysqli_fetch_assoc($cart_check)) {
                $f_id = (int)$item['filmId'];
                mysqli_query($conn, "INSERT INTO RentalDetails (rentalId, filmId) VALUES ($rental_id, $f_id)");
                mysqli_query($conn, "UPDATE Films SET stock = stock - 1 WHERE id = $f_id AND stock > 0");
            }
            
            mysqli_query($conn, "DELETE FROM CartItems WHERE userId = $user_id");
            $msg = "Checkout successful! Please return your films by $dueDate.";
        }
    }
}

$sql = "SELECT CartItems.addedDate, Films.*, Genre.name AS genre_name
FROM CartItems
JOIN Films ON CartItems.filmId = Films.id
JOIN Genre ON Films.genreId = Genre.id
WHERE CartItems.userId = $user_id";
$cart_items = mysqli_query($conn, $sql);

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
    <title>My Cart - CineRent</title>
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
                <a href="my_rentals.php" class="nav-film" style="margin-right: 15px;">My Rentals</a>
                <a href="cart.php" class="nav-film active" style="color: white; font-weight: bold; margin-right: 15px;">Cart</a>
                <span style="color: white; margin-right: 15px;">Halo, <?= htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="nav-register" style="background-color: #555;">Logout</a>
            </nav>
        </div>
    </header>
    <hr class="hr-nav">

    <main class="cart-wrapper">
        <h2>My Rental Cart</h2>

        <?php if ($msg != ''): ?>
            <div class="cart-alert"><?=  htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($cart_items) > 0): ?>
            <div class="cart-list">
                <?php while ($item = mysqli_fetch_assoc($cart_items)): ?>
                    <?php $img_src = !empty($item['poster']) ? $item['poster'] : ($poster[$item['id']] ?? 'assets/Inception.jpg'); ?>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="cart-info">
                            <span class="genre-card"><?=  htmlspecialchars($item['genre_name']) ?></span>
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p><?= htmlspecialchars($item['releaseYear']) ?> &bull; Added <?= htmlspecialchars($item['addedDate']) ?></p>
                        </div>
                        <a href="cart.php?remove=<?= $item['id'] ?>" class="btn-remove">Remove</a>
                    </div>
                <?php endwhile; ?>
            </div>

            <form action="cart.php" method="POST" class="cart-footer">
                <p>Rental duration: <strong>7 Days</strong></p>
                <button type="submit" name="checkout" class="btn-rent">Confirm & Rent Now</button>
            </form>
        <?php else: ?>
            <div class="cart-empty">
                <p>Your cart is empty</p>
                <a href="catalog.php" class="btn-rent" style="margin-top: 15px;">Browse Catalog</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>