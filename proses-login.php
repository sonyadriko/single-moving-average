<?php
    include 'koneksi.php';

    session_start();

    if (isset($_SESSION['id_admin'], $_SESSION['nama'])) {
        header("Location: index.php");
    }

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = (md5($_POST['password']));

        // Use password_hash for hashing and password_verify for verification
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$username' AND password = '$password'");
        // $result = mysqli_fetch_assoc($sql);

        if ($sql->num_rows >= 1) {
            echo "<script>alert('Login Berhasil');</script>";
            echo "<script>location='index.php';</script>";
            $row = mysqli_fetch_assoc($sql);
            $_SESSION['id_admin'] = $row['id_admin'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['email'] = $row['email'];

        } else {
            echo "
            <script type='text/javascript'>
            alert('Username dan Password Salah');
            history.back(self);
            </script>";
        }
    }
?>