<?php 
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
include 'edit.php'; 
include "connection.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
    <script rel="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Countdown - Admin</title>
</head>
<body>
    <div class="adminarea">
            <div class="logout">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
                <a href="countdown.php"><i class="fas fa-hourglass-half"></i></a>
            </div>
            <div class="users">
                <table id="customers">
                <h2>Liste des utilisateurs: </h2>
                <div class="add">
                    <?php 
                    $req = $bdd->prepare("SELECT * FROM member WHERE connected = 'true'");
                    $req->execute();
                    $memberconnected = $req->rowCount();
                    $req = $bdd->prepare("SELECT * FROM member");
                    $req->execute();
                    $memberexist = $req->rowCount();
                    echo '<span style="color: white;">En ligne: '.$memberconnected.'/'.$memberexist.'</span>';
                    $req = $bdd->query("SELECT MAX(id) AS max_id FROM member");
                    while($last = $req->fetch(PDO::FETCH_ASSOC)) {
                        echo("<span class='ghostId' style='display: none;'>".$last['max_id']."</span>");
                    }
                    ?>
                </div>
                    <tr>
                        <th>User</th>
                        <th>Date</th>
                        <th>Email</th>
                        <th>Connecté ?</th>
                        <th>Action</th>
                    </tr>
                    <?php $reponse = $bdd->query('SELECT pseudo, email, statut, connected, id, DATE_FORMAT(created, "%d/%m/%Y") AS created FROM member ORDER BY ID ASC');
                    // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
                    while ($donnees = $reponse->fetch()){

                        if($donnees['connected'] == "true") {
                            echo '<tr><td class="withStat"><div class="all"><span>' . htmlspecialchars($donnees['pseudo']) . '</span><span>'. htmlspecialchars($donnees['statut']) .'</span></div></td><td>' . htmlspecialchars($donnees['created']) . '</td><td>' . htmlspecialchars($donnees['email']) . '</td><td style="color:lightgreen;"><i class="fas fa-circle"></i></td><td><a href="#" class="editIcon' . htmlspecialchars($donnees['id']) . '"><i class="fas fa-user-edit"></i></a> <a href="suprimerUser.php?id='. htmlspecialchars($donnees['id']).'"><i class="fas fa-trash-alt"></i></a></td>';
                            echo '<tr class="editSave' . htmlspecialchars($donnees['id']) . '"><form method="post"><td class="withStat"><div class="all"><input id="prodId" name="prodId" type="hidden" value="'. htmlspecialchars($donnees['id']) .'"><span><input class="newName"  name="newPseudo" placeholder="Nouveau pseudo:" type="text"></span><span><select class="Rank" name="rank"><option value="user">User</option><option value="modo">Modérateur</option><option value="admin">Admin</option></select></span></div></td><td>15/11/2021</td><td><input class="newMail" type="text" name="newMail"></td><td style="color:lightgreen;"><i class="fas fa-circle"></i></td><td><input type="submit" name="save" value="Save"></td></form></tr>';
                        } else {
                            echo '<tr><td class="withStat"><div class="all"><span>' . htmlspecialchars($donnees['pseudo']) . '</span><span>'. htmlspecialchars($donnees['statut']) .'</span></div></td><td>' . htmlspecialchars($donnees['created']) . '</td><td>' . htmlspecialchars($donnees['email']) . '</td><td style="color:red;"><i class="fas fa-circle"></i></td><td><a href="#" class="editIcon' . htmlspecialchars($donnees['id']) . '"><i class="fas fa-user-edit"></i></a> <a href="suprimerUser.php?id='. htmlspecialchars($donnees['id']).'"><i class="fas fa-trash-alt"></i></a></td>';
                            echo '<tr class="editSave' . htmlspecialchars($donnees['id']) . '"><form method="post"><td class="withStat"><div class="all"><input id="prodId" name="prodId" type="hidden" value="'. htmlspecialchars($donnees['id']) .'"><span><input class="newName"  name="newPseudo" placeholder="Nouveau pseudo:" type="text"></span><span><select class="Rank" name="rank"><option value="user">User</option><option value="modo">Modérateur</option><option value="admin">Admin</option></select></span></div></td><td>15/11/2021</td><td><input class="newMail" type="text" name="newMail"></td><td style="color:red;"><i class="fas fa-circle"></i></td><td><input type="submit" name="save" value="Save"></td></form></tr>';
                        }
                    }
                    $reponse->closeCursor();
                    ?>
                <?php
                    if(isset($info)) {
                        echo '<font color="red">'.$info."</font>";
                    }

                ?>
                </table>
            </div>
    </div>
    <script src="js/edit.js"></script>
    <?php 
    $reponse = $bdd->query('SELECT * FROM member ORDER BY ID ASC');
    // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
    while ($donnees = $reponse->fetch()){
    echo('<script>
    document.querySelector(".editSave'.$donnees['id'].'").style.display = "none";
    $(".editIcon'.$donnees['id'].'").click(function(){
        $(".editSave'.$donnees['id'].'").toggle();
    })  
    </script>');
    }
    ?>
</body>
</html>
