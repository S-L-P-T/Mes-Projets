<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: countdown.php");
    exit;
}
include 'connection.php';

if(isset($_POST['register'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $mdp = htmlspecialchars($_POST['pswd']);
    $mdp2 = htmlspecialchars($_POST['pswd2']);
    if(!empty($_POST['pseudo']) AND !empty($_POST['email']) AND !empty($_POST['pswd']) AND !empty($_POST['pswd2'])) {
        $pseudolength = strlen($pseudo);
        if($pseudolength <= 50) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $reqmail = $bdd->prepare("SELECT * FROM member WHERE email = ?");
                $reqmail->execute(array($email));
                $mailexist = $reqmail->rowCount();
                if($mailexist == 0) {
                    if($mdp == $mdp2) {
                        $hash = password_hash($mdp, PASSWORD_DEFAULT);
                        $req = $bdd->prepare('INSERT INTO member(pseudo, email, pswd, color, statut, connected) VALUES(:pseudo, :email, :pswd, :color, :statut, :connected)');
                        $req->execute(array(
                            'pseudo' => $pseudo,
                            'email' => $email,
                            'pswd' => $hash,
                            'color' => "red",
                            'statut' => "user",
                            'connected' => "false"));
                        $info = "Votre compte a bien été créé !";
                        header('Location: login.php');
                    } else {
                        $info = "Vos mots de passes ne correspondent pas !";
                    }
                } else {
                    $info = "Adresse email déjà utilisée !";
                }
            } else {
                $info = "Votre adresse email n'est pas valide !";
            }
        } else {
            $info = "Votre pseudo ne doit pas dépasser 50 caractères !";
        }
    } else {
        $info = "Tous les champs doivent être complétés !";
    }
}
?>
