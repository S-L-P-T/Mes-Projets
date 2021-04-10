<?php 
session_start();
include "connection.php";
$task = "list";
$author = $_SESSION['pseudo'];



if(array_key_exists("task", $_GET)) {
    $task = $_GET['task'];
}

if($task == "write"){
    postMessage();
} else {
    getMessage();
}

function getMessage(){
    global $bdd;
    $response = $bdd->query('SELECT * FROM messages ORDER BY id ASC'); 
    $messages = $response->fetchAll();
    echo json_encode($messages);
}

function postMessage() {
    global $author;
    global $bdd;
    $content = $_POST['message'];
    if(empty($content)) {
        echo json_encode(["status" => "error", "message" => "Le champs du message est vide !"]);
        return;
    } else {
        $content = $_POST['message'];
        $id = $_SESSION['id'];
        if (empty($_POST["color"])) {
            echo("vide");
            echo($_SESSION['color']);
            $query = $bdd->prepare('INSERT INTO messages SET pseudo = :pseudo, statut = :statut, chat = :chat, color = :color');
            if($_SESSION['statut'] == "admin") {
                $query->execute(["pseudo" => $author, "statut" => "<i class='fas fa-user-shield'></i>", "chat" => $content, "color" => $_SESSION['color']]);
            } else {
                $query->execute(["pseudo" => $author, "statut" => "", "chat" => $content, "color" => $_SESSION['color']]);
            }
            echo json_encode(["status" => "success", "message" => "Le message est bien pris en compte !"]);
        } else {
            echo("OK");
            $color = $_POST['color'];
            $author = $_SESSION['pseudo'];
            $queryColor = $bdd->prepare('UPDATE member SET color = :color WHERE id = :id');
            $queryColor->execute(["color" => $color, "id" => $id]);
            $queryUpColor = $bdd->prepare('UPDATE messages SET color = :color WHERE pseudo = :pseudo');
            $queryUpColor->execute(["color" => $color, "pseudo" => $author]);
            $_SESSION['color'] = $color;
            $query = $bdd->prepare('INSERT INTO messages SET pseudo = :pseudo, statut = :statut, chat = :chat, color = :color');
            if($_SESSION['statut'] == "admin") {
                $query->execute(["pseudo" => $author, "statut" => "<i class='fas fa-user-shield'></i>", "chat" => $content, "color" => $_SESSION['color']]);
            } else {
                $query->execute(["pseudo" => $author, "statut" => "", "chat" => $content, "color" => $_SESSION['color']]);
            }
            echo json_encode(["status" => "success", "message" => "Le message est bien pris en compte !"]);
        }
    }
}
?>
