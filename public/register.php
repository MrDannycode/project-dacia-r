<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nume = $_POST["nume"];
    $email = $_POST["email"];
    $parola = password_hash($_POST["parola"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilizatori (nume, email, parola) VALUES (:nume, :email, :parola)";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([
            ':nume' => $nume,
            ':email' => $email,
            ':parola' => $parola
        ]);

        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
          echo "Email already exists!"; //[v2]echo "Database error: " . $e->getMessage();
    }
}
?>

<h2>Sign Up</h2>
<a href="index.php">Home</a>
<form method="POST">
    Name: <input type="text" name="nume" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="parola" required><br><br>
    <button type="submit">Register</button>
</form>