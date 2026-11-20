<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirmpassword'] ?? ''); 
    $gender = trim($_POST['gender'] ?? '');
    $dob = trim($_POST['dob'] ?? '');

    if (strlen($username) < 4 || strlen($username) > 20)  {
        $error_msg = "Username must be between 4 and 20 characters.";
    }

    elseif (!str_ends_with($email, '@gmail.com')) {
        $error_msg = "Email must end with @gmail.com.";
    }

    elseif (strlen($password) < 8) {
        $error_msg = "Password must be at least 8 characters.";
    }

    elseif ($password !== $confirm) {
        $error_msg = "Passwords do not match.";
    }

    elseif ($gender !== 'Male' && $gender !== 'Female') {
        $error_msg = "Please select a valid gender.";
    }

    elseif (strtotime($dob) >= time() || empty($dob)) {
        $error_msg = "Date of Birth must be a valid date in the past.";
    }

    else {
        $cek_unik = mysqli_query($conn, "SELECT * FROM Users WHERE username = '$username' OR email = '$email'");
        if (mysqli_num_rows($cek_unik) > 0) {
            $error_msg = "Username or Email is already registered.";
        }
        else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO Users (username, email, password, gender, dateOfBirth) VALUES ('$username', '$email', '$hashed_password', '$gender', '$dob')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'Member';
                
                header("Location: catalog.php");
                exit;
            } 
            else {
                $error_msg = "Failed to create account: " . mysqli_error($conn);
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register CineRent</title>
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
    
    <div class="container">
        <div class="regtitle">
            <h2>CineRent</h2>
            <h3>Create Account</h3>
        </div>
        <p class="regsubtitle">Join CineRent and start renting today</p>

        <?php if ($error_msg != ''): ?>
            <div class="error-alert">&#9888; <?= $error_msg ?></div>
        <?php endif; ?>

        <div class="regform">
            <form action="" method="post" >
                <div class="formgroup">
                    <label for="username" class="formlabel">Username</label>
                    <input type="text" name="username" id="username" placeholder="4-20 characters" value="<?= $_POST['username'] ?? '' ?>">
                </div>
                
                <div class="formgroup">
                    <label for="email" class="formlabel">Email</label>
                    <input type="email" name="email" id="email" placeholder="example@gmail.com" value="<?= $_POST['email'] ?? '' ?>">
                </div>

                <div class="flexatas">
                <div class="formgroup">
                    <label for="password" class="formlabel">Password</label>
                    <input type="password" name="password" id="password" placeholder="Min. 8 characters">
                </div>
                <div class="formgroup">
                    <label for="confirmpassword" class="formlabel">Confirm Password</label>
                    <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Repeat Password">
                </div>
                </div>

                <div class="flexbawah">
                <div class="formgroup">
                    <label class="formlabel">Gender</label>
                    <select name="gender">
                    <option value="">-- Select --</option>
                    <option value="Male"<?= (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="formgroup">
                    <label for="dob" class="formlabel">Date of Birth</label>
                    <input type="date" name="dob" id="dob" value="<?= $_POST['dob'] ?? '' ?>">
                </div>
                </div>
                
                <button type="submit" class="btn-reg">Create Account</button>
                <p class="already">
                Already have an account? <a href="login.php" class="login">Sign in</a>
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