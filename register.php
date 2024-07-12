<?php
// Include database connection file
include "conn/koneksi.php";

if (isset($_POST['Register'])) {
    // Retrieve and sanitize form data
    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['telp']);

    // Hash the password
    $password_hash = md5($password); // It is recommended to use a stronger hashing algorithm like bcrypt

    // Check if the NIM or username already exists
    $check_nim = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nim='$nim'");
    $check_username = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE username='$username'");
    
    if (mysqli_num_rows($check_nim) > 0) {
        echo "<script>alert('NIM already exists. Please use another one.')</script>";
    } elseif (mysqli_num_rows($check_username) > 0) {
        echo "<script>alert('Username already exists. Please choose another one.')</script>";
    } else {
        $sql = "INSERT INTO mahasiswa (nim, nama, username, password, telp) VALUES ('$nim', '$nama', '$username', '$password_hash', '$telp')";
        if (mysqli_query($koneksi, $sql)) {
            echo "<script>alert('Registration successful. Please login.');
            window.location.href = 'index.php';
            </script>";
        } else {
            echo "<script>alert('Registration failed. Please try again.')</script>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Suara Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5777ba;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            padding: 20px;
            box-sizing: border-box;
        }
        .card h4 {
            text-align: center;
            color: #ff8c00;
            margin-bottom: 20px;
        }
        .input_field {
            margin-bottom: 15px;
        }
        .input_field label {
            display: block;
            margin-bottom: 5px;
            color: #5777ba;
        }
        .input_field input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .input_field svg {
            margin-right: 10px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #5777ba;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #e67e00;
        }
    </style>
</head>
<body>
    <div class="card">
        <h4 style="color: #5777ba;"class="text">Sign-in</h4>

        <form method="POST">
            <div class="input_field">
                <label for="nim">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                    </svg> NIM 
                </label>
                <input id="nim" type="text" name="nim" required>
            </div>
            <div class="input_field">
                <label for="nama">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                    </svg> Nama 
                </label>
                <input id="nama" type="text" name="nama" required>
            </div>
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
            <div class="input_field">
                <label for="telp">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M3.654 1.328a1.99 1.99 0 0 1 2.868 0l.91.91a1.99 1.99 0 0 1 0 2.869L6.272 6.384a.678.678 0 0 0-.125.755 11.682 11.682 0 0 0 3.428 4.73 11.682 11.682 0 0 0 4.73 3.429.678.678 0 0 0 .755-.125l1.277-1.276a1.99 1.99 0 0 1 2.869 0l.91.91a1.99 1.99 0 0 1 0 2.868l-.853.853a2.774 2.774 0 0 1-2.68.732 15.24 15.24 0 0 1-9.292-4.127 15.24 15.24 0 0 1-4.127-9.292 2.774 2.774 0 0 1 .732-2.68l.853-.853z"/>
                    </svg> Telepon
                </label>
                <input id="telp" type="text" name="telp" required>
            </div>
            <input type="submit" name="Register" value="Register" class="btn">
        </form>
        <div id="footer" style="text-align: center;">
      <p>Suara Mahasiswa<br>
                <!-- Version 4.0.0 --> © 2024. All Right Reserved</p>
         <div class="footer-nav">
         <ul>

    </div>
</body>
</html>
