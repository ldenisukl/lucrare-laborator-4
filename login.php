<?php

session_start();

$host = "localhost";
$dbname = "login_db";
$username = "root";
$password = "root";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = new mysqli($host, $username, $password, $dbname);
    if ($mysqli->connect_errno) {
        die("Eroare de conexiune: " . $mysqli->connect_error);
    }

    if (empty($_POST["email"]) || empty($_POST["password"])) {
        die("Toate câmpurile sunt obligatorii");
    }

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Eroare SQL: " . $mysqli->error);
    }
    $stmt->bind_param("s", $_POST["email"]);
    if (!$stmt->execute()) {
        die("Eroare la executarea interogării: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        die("Nu există niciun utilizator cu această adresă de email.");
    }

    $user = $result->fetch_assoc();

    if (!password_verify($_POST["password"], $user["password_hash"])) {
        die("Parola introdusă este incorectă.");
    }
session_start();
session_regenerate_id();

    $_SESSION["user_id"] = $user["id"];
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Login</title>
</head>
<body>
    <form method="post">
        <h1>Login</h1>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email"
          value="<?=isset($_POST["email"]) ? $_POST["email"] : ""?>">

        <label for="password">Password:</label>
        <input type="password" name="password" id="password">

        <button>Login</button>
    </form>
</body>
</html>
