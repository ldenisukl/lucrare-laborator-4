<?php

$host = "localhost";
$dbname = "login_db";
$username = "root";
$password = "root";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = new mysqli($host, $username, $password, $dbname);
    if ($mysqli->connect_errno) {
        die("Eroare de conexiune: " . $mysqli->connect_error);
    }

    if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
        die("Toate câmpurile sunt obligatorii");
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        die("Formatul adresei de email nu este valid");
    }

    if (strlen($_POST["password"]) < 6 || !preg_match("/[a-z]/i", $_POST["password"]) || !preg_match("/[0-9]/", $_POST["password"])) {
        die("Parola trebuie să aibă cel puțin 6 caractere, să conțină cel puțin o literă și cel puțin o cifră");
    }

    if ($_POST["password"] !== $_POST["password_confirmation"]) {
        die("Parolele nu coincid");
    }

    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql_check = "SELECT * FROM user WHERE name = ? OR email = ?";
    $stmt_check = $mysqli->prepare($sql_check);
    if (!$stmt_check) {
        die("Eroare SQL: " . $mysqli->error);
    }
    $stmt_check->bind_param("ss", $_POST["name"], $_POST["email"]);
    if (!$stmt_check->execute()) {
        die("Eroare la executarea interogării: " . $stmt_check->error);
    }

    $result = $stmt_check->get_result();
    if ($result->num_rows > 0) {
        die("Utilizatorul cu acest nume sau adresă de email există deja.");
    }

    $sql_insert = "INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?)";
    $stmt_insert = $mysqli->prepare($sql_insert);
    if (!$stmt_insert) {
        die("Eroare SQL: " . $mysqli->error);
    }
    $stmt_insert->bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);
    if (!$stmt_insert->execute()) {
        die("Eroare la executarea interogării: " . $stmt_insert->error);
    }

    
    header("Location: succes.php");
exit;

}
