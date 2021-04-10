<?php
session_start();
include 'connection.php';

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: countdown.php");
    exit;
}

if(isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['email']);
    $mdp = htmlspecialchars($_POST['pswd']);
    if(!empty($_POST['email']) AND !empty($_POST['pswd'])) {
        $req = $bdd->prepare("SELECT * FROM member WHERE email = ?");
        $req->execute(array($email));
        $memberexist = $req->rowCount();
        if($memberexist == 1) {
            $userinfo = $req->fetch();
            $id = $userinfo['id'];
            $hash = $userinfo['pswd'];
            $statut = $userinfo['statut'];
            $color = $userinfo['color'];
            if(password_verify($mdp, $hash)) {
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION['id'] = $id;
                $_SESSION['pseudo'] = $userinfo['pseudo'];
                $_SESSION['statut'] = $statut;
                $_SESSION['color'] = $color;
                $reponse = $bdd->prepare("UPDATE member SET connected='true' WHERE id=$id");
                $reponse->execute();
                header("Location: countdown.php");
            } else {
                $info = "Email ou Mot de passe faux :(";
            }

        } else {
            $info = "Email ou Mot de passe faux :(";
        }
    } $info = "Tous les champs doivent être complétés !";
}
?>
