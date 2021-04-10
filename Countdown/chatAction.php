<?php 
if(isset($_PPOST['sendMessage'])) {
    $message = htmlspecialchars($_POST['message']);
    $pseudo = $_SESSION['pseudo'];
}

?>