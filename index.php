<?php
session_start();
if (isset($_SESSION["user_id"])){
    $mysqli=require __DIR__ ."process-singup.php";
    $sql = "SELECT*FROM user
            WHERE id={$_SESSION["user_id"]}";
            $result = $mysqli->query($sql);
            $user =$result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Home</h1>
    <p><a href="logauut.php">Log out</a></p>

<p>Privet da tu esti sexi<?= htmlspecialchars( $user["name"])?> </p>

    <?php if(isset($user)): ?>

    <?php else: ?>

        <p><a href="login.php">Login</a> or <a href="singup.php">Sign Up</a></p>
    <?php endif; ?>
</body>
</html>
