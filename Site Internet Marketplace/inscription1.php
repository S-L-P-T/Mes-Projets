<?php

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if (isset($_POST['forminscription']))
{
	if (!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['pseudo']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']))
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail = htmlspecialchars($_POST['mail']);
		$mail2 = htmlspecialchars($_POST['mail2']);
		$mdp = htmlspecialchars($_POST['mdp']);
		$mdp2 = htmlspecialchars($_POST['mdp2']);

		$pseudolength = strlen($pseudo);
		if ($pseudolength <= 255)
		{
			if ($mail == $mail2)
			{
				if (filter_var($mail, FILTER_VALIDATE_EMAIL))
				{
					$reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
					$reqmail->execute(array($mail));
					$mailexist = $reqmail->rowCount();
					if ($mailexist == 0)
					{
						if ($mdp == $mdp2)
						{
							$insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, motdepasse) VALUES(?, ?, ?)");
							$insertmbr->execute(array($pseudo, $mail, $mdp));
							$erreur = "Merci de vous être inscris <a href=\"connexion1.php\">Me connecter<a/>";
						}
						else
						{
							$erreur = "Vos mots de passses ne correspondent pas !";
						}
					}
					else
					{
						$erreur = "Adresse mail déjà utilisée !";
					}
				}
				else
				{
					$erreur = "Votre adresse mail n'est pas valide !";
				}
			}
			else
			{
				$erreur = "Vos adresses mail ne correspondent pas !";
			}
		}
		else
		{
			$erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent être complétés !";
	}
}

?>
<html>
	<head>
		<title>Inscription</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div align="center">
			<h1>Inscription</h1>
			<br /><br />
			<form method="POST" action="">
				<table>
					<tr>
						<td align="right">
							<label for="pseudo">Pseudo :</label>
						</td>
						<td>
							<input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if (isset($pseudo)) { echo $pseudo; } ?>">
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="mail">Mail :</label>
						</td>
						<td>
							<input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if (isset($mail)) { echo $mail; } ?>">
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="mail2">Confirmation du mail :</label>
						</td>
						<td>
							<input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" align="right" value="<?php if (isset($mail2)) { echo $mail2; } ?>">
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="mdp">Mot de passe :</label>
						</td>
						<td>
							<input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp">
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="mdp2">Confirmation du mot de passe :</label>
						</td>
						<td>
							<input style="focus" type="password" placeholder="Confirmez mot de passe" id="mdp2" name="mdp2">
						</td>
					</tr>
				</table>
				<br />
				<input type="submit" name="forminscription" value="Je m'inscris">
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
