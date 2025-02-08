<?php
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $passwordRepeat = $_POST["password_2"];
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $errors = [];

        if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
            $errors[] = "All fields are required";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is not valid";
        }
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        if ($password !== $passwordRepeat) {
            $errors[] = "Passwords do not match";
        }

        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Email already exists!";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {
            $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            } else {
                die("Something went wrong");
            }
        }
    }

    if (isset($_POST["login"])) {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["user"] = $user["username"];
            header("Location: flappybird/flappy.html");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Invalid email or password</div>";
        }
    }
}
?>
