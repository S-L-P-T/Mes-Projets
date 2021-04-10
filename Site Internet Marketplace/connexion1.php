<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if (isset($_POST['formconnect']))
{
	$mailconnect = htmlspecialchars($_POST['mailconnect']);
	$mdpconnect = htmlspecialchars($_POST['mdpconnect']);
	if (!empty($mailconnect) AND !empty($mdpconnect))
	{
		$requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motdepasse = ?");
		$requser->execute(array($mailconnect, $mdpconnect));
		$userexist = $requser->rowCount();
		if ($userexist == 1)
		{
			$userinfo = $requser->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['pseudo'] = $userinfo['pseudo'];
			$_SESSION['mail'] = $userinfo['mail'];
			header("Location: profil1.php?id=".$_SESSION['id']);
		}
		else
		{
			$erreur = "Votre mail ou mot de passe est incorrect !";
		}
	}
	else
	{
		$erreur = "Veuillez entrez vos identifiants !";
	}
}

?>
<html>
	<head>
		<title>Connexion</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div align="center">
			<h1>Connexion</h1>
			<br /><br />
			<form method="POST" action="">
				<input type="email" name="mailconnect" placeholder="Mail">
				<input type="password" name="mdpconnect" placeholder="Mot de passe">
				<input type="submit" name="formconnect" value="Connexion">
			</form>
			<?php
			if (isset($erreur))
			{
				echo '<font color="red">'.$erreur."</font>";
			}
			?>
		</div>
	</body>
</html>