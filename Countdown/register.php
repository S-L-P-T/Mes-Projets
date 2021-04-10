<?php include 'registerAction.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countdown - Inscription</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="" method="POST" name="register" class="login">
        <h3>Inscription:</h3>
        <input type="text" name="pseudo" placeholder="Votre pseudo:" required>
        <input name="email" type="email" placeholder="Votre email:" required>
        <input type="password" name="pswd" placeholder="Votre mot de passe:" required>
        <input type="password" name="pswd2" placeholder="Retapez votre mot de passe:" required>
        <input type="submit" name="register" placeholder="Login !" value="Inscrivez-Vous">
        <a href="login.php" class="already">Déjà inscrit ?</a>
        <?php
	         if(isset($info)) {
	            echo '<font color="red">'.$info."</font>";
	         }
	    ?>
    </form>
</body>
</html>