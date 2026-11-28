<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    
    $query = mysqli_query($conn, "SELECT * FROM Users WHERE email = '$email'");

    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            if(isset($_POST['remember'])) {
                setcookie('remember_user', $user['id'], time() + (7 * 24 * 60 * 60), "/");
            }
            header("Location: index.php");
            exit;
        } else {
            $error_msg = "Incorrect password.";
        }
    } 
    else {
        $error_msg = "Email is not registered.";
    }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CineRent</title>
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

    <div class="container">
        <div class="regtitle">
            <h2>CineRent</h2>
            <h3>Welcome Back</h3>
        </div>
        <p class="regsubtitle">Sign in to continue renting</p>

        <?php if ($error_msg != ''): ?>
            <div class="error-alert">&#9888; <?= $error_msg ?></div>
        <?php endif; ?>

        <div class="regform">
            <form action="" method="post">
                <div class="formgroup">
                    <label for="email" class="formlabel">Email</label>
                    <input type="email" name="email" id="email" placeholder="example@gmail.com" value="<?= $_POST['email'] ?? '' ?>" required>
                </div>

                <div class="formgroup">
                    <label for="password" class="formlabel">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>
                
                <div class="formgroup" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="remember" id="remember" style="width: auto;">
                    <label for="remember" style="font-size: 13px; color: var(--muted); cursor: pointer;">Remember me (1 week)</label>
                </div>

                <button type="submit" class="btn-reg">Sign In</button>
                <p class="already">
                New to CineRent? <a href="register.php" class="login">Create an account</a>
                </p>
            </form>
        </div>
    </div>

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

