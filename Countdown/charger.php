<?php
    include "connection.php";
    // on récupère les messages ayant un id plus grand que celui donné
    $requete = $bdd->prepare('SELECT * FROM messages ORDER BY id ASC');
    $requete->execute();

    $messages = null;

    // on inscrit tous les nouveaux messages dans une variable
    while($donnees = $requete->fetch()){
        $messages .= "<p>" . $donnees['pseudo'] . " exprime : " . $donnees['chat'] . "</p>";
    }

    echo $messages; // enfin, on retourne les messages à notre script JS
?>