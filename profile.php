<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$msg = '';
$error_msg = '';
$res = mysqli_query($conn, "SELECT * FROM Users WHERE id = $user_id");
$user = mysqli_fetch_assoc($res);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $gender = trim($_POST['gender'] ?? '');
    $dob = trim($_POST['dob'] ?? '');

    if (strlen($username) < 4 || strlen($username) > 20) {
        $error_msg = "Username must be between 4 and 20 characters.";
    }

    else if (!str_ends_with($email, '@gmail.com')) {
        $error_msg = "Email must end with @gmail.com";
    }

    else if ($gender !== 'Male' && $gender !== 'Female') {
        $error_msg = "Please select a valid gender.";
    }

    else if(empty($dob) || strtotime($dob) >= time()) {
        $error_msg = "Date of Birth must be a valid date in the past.";
    }

    else {
        $cek = mysqli_query($conn, "SELECT id FROM Users WHERE (username = '$username' OR email = '$email') AND id != $user_id");
        if (mysqli_num_rows($cek) > 0) {
           $error_msg = "Username or Email is already taken by another account.";
        }
        else {
            $sql_up = "UPDATE Users SET username = '$username', email = '$email', gender = '$gender', dateOfBirth = '$dob' WHERE id = $user_id";
            if (mysqli_query($conn, $sql_up)) {
                $_SESSION['username'] = $username;
                $msg = "Profile updated successfully!";
                $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Users WHERE id = $user_id"));
            }
            else {
                $error_msg = "Failed to update profile";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $current_pw = trim($_POST['current_password'] ?? '');
    $new_pw = trim($_POST['new_password'] ?? '');
    $confirm_pw = trim($_POST['confirm_password'] ?? '');

    if (!password_verify($current_pw, $user['password'])) {
        $error_msg = "Current password is incorrect.";
    }
    else if (strlen($new_pw) < 8) {
        $error_msg = "New password must be at least 8 characters.";
    }
    else if ($new_pw !== $confirm_pw) {
        $error_msg = "New passwords do not match.";
    }
    else {
        $hashed = password_hash($new_pw, PASSWORD_DEFAULT);
        if(mysqli_query($conn, "UPDATE Users SET password = '$hashed' WHERE id = $user_id")) {
            $msg = "Password updated successfully!";
            $user['password'] = $hashed;
        }
        else {
            $error_msg = "Failed to update password.";
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $del_pw = trim($_POST['delete_password'] ?? '');

    if (!password_verify($del_pw, $user['password'])) {
        $error_msg = "Incorrect password. Account deletion cancelled.";
    }
    else {
        mysqli_query($conn, "DELETE FROM CartItems WHERE userId = $user_id");
        if (mysqli_query($conn, "DELETE FROM Users WHERE id = $user_id")) {
            session_unset();
            session_destroy();
            setcookie('remember_user', '', time() - 3600,"/");
            header("Location: index.php");
            exit;
        }
        else {
            $error_msg = "Cannot delete account because it still has rental records.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CineRent</title>
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
                <?php if(strtolower($_SESSION['role'] ?? '') === 'admin'): ?>
                    <a href="admin.php" class="nav-film" style="color: #e50914; font-weight: bold;">Admin Dashboard</a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="kanan">
            <nav>
                <a href="my_rentals.php" class="nav-film" style="margin-right: 15px;">My Rentals</a>
                <a href="cart.php" class="nav-film" style="color: white; font-weight: bold; margin-right: 15px;">Cart</a>
                <a href="profile.php" class="nav-film active" style="color: white; margin-right: 15px;">Halo, <?= htmlspecialchars($_SESSION['username']); ?></a>
                <a href="logout.php" class="nav-register" style="background-color: #555;">Logout</a>
            </nav>
        </div>
    </header>
    <hr class="hr-nav">

    <main class="cart-wrapper">
        <h2>My Profile</h2>

        <?php if ($msg != ''): ?>
            <div class="cart-alert"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?php if ($error_msg != ''): ?>
            <div class="error-alert">&#9888; <?= htmlspecialchars($error_msg) ?></div>
        <?php endif ?>

        <section class="admin-section">
            <h3>Update Profile Information</h3>
            <form action="profile.php" method="post">
                <div class="formgroup">
                    <label class="formlabel">Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="formgroup">
                    <label class="formlabel">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="flexbawah">
                    <div class="formgroup">
                        <label class="formlabel">Gender</label>
                        <select name="gender" required>
                            <option value="Male" <?= ($user['gender'] == 'Male') ? 'selected': '' ?>>Male</option>
                            <option value="Female" <?= ($user['gender'] == 'Female') ? 'selected': '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="formgroup">
                        <label class="formlabel">Date of Birth</label>
                        <input type="date" name="dob" value="<?= htmlspecialchars($user['dateOfBirth']) ?>" required>
                    </div>
                </div>
                <button type="submit" name="update_profile" class="btn-rent">Save Changes</button>
            </form>
        </section>

        <section class="admin-section">
            <h3>Change Password</h3>
            <form action="profile.php" method="POST">
                <div class="formgroup">
                    <label class="formlabel">Current Password</label>
                    <input type="password" name="current_password" placeholder="Enter current password" required>
                </div>
                <div class="flexatas">
                    <div class="formgroup">
                        <label class="formlabel">New Password</label>
                        <input type="password" name="new_password" placeholder="Min. 8 characters" required>
                    </div>
                    <div class="formgroup">
                        <label class="formlabel">Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="Repeat new password" required>
                    </div>
                </div>
                <button type="submit" name="update_password" class="btn-rent">Update Password</button>
            </form>
        </section>
        
        <section class="admin-section" style="border-color: rgba(229, 9, 20, 0.4);">
            <h3 style="color: #ff6969;">Delete Account</h3>
            <p style="color: var(--muted); font-size: 14px; margin-bottom: 16px;">This action is permanent and cannot be undone. Enter your password to confirm.</p>
            <form action="profile.php" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete your account?')">
                <div class="formgroup">
                    <label class="formlabel">Confirm Password</label>
                    <input type="password" name="delete_password" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="delete_account" class="btn-remove" style="border: none; cursor: pointer; padding: 10px 20px;">Delete My Account</button>
            </form>
        </section>
    </main>
</body>
</html>