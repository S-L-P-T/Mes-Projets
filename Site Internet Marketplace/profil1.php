<?php

session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if (isset($_GET['id']) AND $_GET['id'] > 0)
{
	$getid = intval($_GET['id']);
	$requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
	$requser->execute(array($getid));
	$userinfo = $requser->fetch();
?>
<html>
	<head>
		<title>Profil de <?php echo $userinfo['pseudo']; ?></title>
		<meta charset="utf-8">
	</head>
	<body>
		<div align="center">
			<h1>Profil de <?php echo $userinfo['pseudo']; ?></h1>
			<br /><br />
			<a>Pseudo = <?php echo $userinfo['pseudo']; ?><a/>
			<br />
			<a>Mail = <?php echo $userinfo['mail']; ?><a/>
			<br />
			<?php
			if (isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id'])
			{
			?>
			<a href="#">Editer mon profil<a/>
			<a href="déconnexion.php">Se déconnecter<a/>
			<?php
			}
			?>
		</div>
	</body>
</html>
<?php
}
?>