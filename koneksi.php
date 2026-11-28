<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "cinerent";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_ACTIVE && !isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
    $cookie_uid = (int)$_COOKIE['remember_user'];
    $u_res = mysqli_query($conn, "SELECT * FROM Users WHERE id = $cookie_uid");
    if ($u_res && mysqli_num_rows($u_res) == 1) {
        $u_row = mysqli_fetch_assoc($u_res);
        $_SESSION['user_id'] = $u_row['id'];
        $_SESSION['username'] = $u_row['username'];
        $_SESSION['role'] = $u_row['role'];
    }
}
?>