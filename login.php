<div class="card" style="border-radius: 10px; padding: 50px; width: 500px; margin: 0 auto; margin-top: 10%; margin-left: auto; margin-right: 0; float: right;">
<h4 style="text-align: left;" class="text">Log in</h4><br>

    <form method="POST">
        <div class="input_field">
            <label for="username">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                </svg> Username 
            </label>
            <input id="username" type="text" name="username" required>
        </div>
        <div class="input_field">
            <label for="password">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                </svg> Password
            </label>
            <input id="password" type="password" name="password" required>
        </div>
        <input type="submit" name="login" value="Login" class="btn #5777ba;" style="width: 100%; border-radius: 10px;">
        <a href="register.php">Belum punya akun? Sign-in</a>
    </form>
    <div id="footer" style="text-align: center;">
      <p>Suara Mahasiswa<br>
                <!-- Version 4.0.0 --> © 2024. All Right Reserved</p>
         <div class="footer-nav">
         <ul>
</div>
<?php
include "conn/koneksi.php";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $password_hash = md5($password); // Assuming passwords are stored as MD5 hashes

    // Check mahasiswa
    $sql = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE username='$username' AND password='$password_hash'");
    $cek = mysqli_num_rows($sql);
    $data = mysqli_fetch_assoc($sql);

    // Check petugas
    $sql2 = mysqli_query($koneksi, "SELECT * FROM petugas WHERE username='$username' AND password='$password_hash'");
    $cek2 = mysqli_num_rows($sql2);
    $data2 = mysqli_fetch_assoc($sql2);

    if ($cek > 0) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['data'] = $data;
        $_SESSION['level'] = 'mahasiswa';
        header('Location: mahasiswa/');
    } elseif ($cek2 > 0) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['data'] = $data2;
        if ($data2['level'] == "admin") {
            header('Location: admin/');
        } elseif ($data2['level'] == "petugas") {
            header('Location: petugas/');
        }
    } else {
        echo "<script>alert('Login gagal. Username atau Password anda salah.')</script>";
    }
}
?>