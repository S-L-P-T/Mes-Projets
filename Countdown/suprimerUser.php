<?php
include "connection.php";
$id = $_GET['id'];
$sql = "DELETE FROM member WHERE id=$id";

// use exec() because no results are returned
$bdd->exec($sql);
header("Location: admin.php");