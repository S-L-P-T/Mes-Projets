<?php
try {
    $bdd = new PDO ('mysql:host=localhost;dbname=countdown', 'root', '');
} 
catch(Exception $e) {
   die('Erreur :'.$e->getMessage());
}

?>