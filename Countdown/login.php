<?php include 'loginAction.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countdown - Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="" method="POST" name="login" class="login">
        <h3>Connexion:</h3>
        <input name="email" type="email" placeholder="Votre email:" required>
        <input type="password" name="pswd" placeholder="Votre mot de passe:" required>
        <input type="submit" name="login" placeholder="Login !" value="Connectez-Vous">
        <a href="register.php" class="already">Pas encore inscrit ?</a>
        <?php
	         if(isset($info)) {
	            echo '<font color="red">'.$info."</font>";
	         }
	    ?>
    </form>
</body>
</html>