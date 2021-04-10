<?php 
include 'connection.php';
if(isset($_POST['save'])) {
    $pseudoNew = htmlspecialchars($_POST['newPseudo']);
    $statutNew = $_POST['rank'];
    $emailNew = htmlspecialchars($_POST['newMail']);
    $id = $_POST['prodId'];
    if(!empty($_POST['newPseudo']) OR !empty($_POST['rank']) OR !empty($_POST['newMail'])) {
        $pseudolength = strlen($pseudoNew);
        if($pseudolength <= 50) {
            if(filter_var($emailNew, FILTER_VALIDATE_EMAIL)) {
                $reqmail = $bdd->prepare("SELECT * FROM member WHERE email = ?");
                $reqmail->execute(array($emailNew));
                $mailexist = $reqmail->rowCount();
                if($mailexist == 1) {
                    $req = $bdd->prepare('UPDATE member SET pseudo = :pseudo, statut = :statut, email = :email WHERE id = :id');
                    $req->execute(["pseudo" => $pseudoNew, "statut" => $statutNew, "email" => $emailNew, "id" => $id]);
                    $info = "Votre compte a bien été modifié !";
                    header('Location: admin.php');
                } else {
                    $info = "email déjà existant";
                }
            } else {
                $info = "Email pas valide";
            }
        } else {
            $info = "Pseudo trop long";
        }
    } else {
        $info = "Tous les champs doivent être complétés !";
    }

    if(empty($_POST['newPseudo']) AND empty($_POST['rank']) AND empty($_POST['newMail'])) {
        header('Location: admin.php');
    } elseif(!empty($_POST['newPseudo'])) {
        $pseudolength = strlen($pseudoNew);
        if(!empty($pseudolength <= 50)) {
            $req = $bdd->prepare('UPDATE member SET pseudo = :pseudo WHERE id = :id');
            $req->execute(["pseudo" => $pseudoNew, "id" => $id]);
            $info = "Votre compte a bien été modifié !";
            header('Location: admin.php');
        }
    } elseif(!empty($_POST['rank'])) {
        $req = $bdd->prepare('UPDATE member SET statut = :statut WHERE id = :id');
        $req->execute(["statut" => $statutNew, "id" => $id]);
    } elseif(!empty($_POST['emailNew'])) {
        if(filter_var($emailNew, FILTER_VALIDATE_EMAIL)) {
            $reqmail = $bdd->prepare("SELECT * FROM member WHERE email = ?");
            $reqmail->execute(array($emailNew));
            $mailexist = $reqmail->rowCount();
            if($mailexist == 1) {
                $req = $bdd->prepare('UPDATE member SET email = :email WHERE id = :id');
                $req->execute(["email" => $emailNew, "id" => $id]);
                $info = "Votre compte a bien été modifié !";
                header('Location: admin.php');
            }
        }
    }
}
?>