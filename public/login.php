<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $parola = $_POST["parola"];

    $sql = "SELECT * FROM utilizatori WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    /*debugging eroare la login
    var_dump($user);
    var_dump(password_verify($password, $user["parola"]));
    exit();
    era mai inainte 2 utilizatori cu acelasi nume, cand am creat un utilizator nou a mers loginul*/

    if ($user && password_verify($parola, $user["parola"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["nume"];

        header("Location: index.php");
        exit();

    } else {
        echo "Invalid email or password!";
    }
}
?>

<h2>Login</h2>
<a href="index.php">Home</a>
<a href="register.php">Sign Up</a>
<form method="POST">
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="parola" required><br><br>
    <button type="submit">Login</button>
</form>