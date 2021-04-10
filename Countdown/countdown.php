<?php 
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include "connection.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countdown - Chat</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
    <script src="js/timer.js"></script>
    <script rel="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="countdownPage">
        <div class="countdown">
            <div class="logout">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
                <?php if($_SESSION['statut'] == "admin"){
                    echo '<a href="admin.php"><i class="fas fa-users-cog"></i></a>';
                }
                ?>
            </div>
            <div class="count">
                    <span>Countdown du Nouvel An</span>
                    <span>Il nous reste:</span>
                    <span id="time"></span>
                    <span class="jaco" style="display: none;"><?php echo($_SESSION["statut"]); ?></span>
            </div>
            <div class="arrow">
                <span class="arr">^</span>
                <div class="network">
                    <a href="https://twitter.com/intent/tweet?url=https%3A%2F%2Fcountdownyear.000webhostapp.com%2F&text=Tout+seul+?+Venez+fêter+le+nouvel+an+avec+des+gens&hashtags=nouvelan2021"><i class="fab fa-twitter-square"></i></a>
                    <a><i class="fab fa-facebook-square"></i></a>
                    <a><i class="fas fa-envelope-square"></i></a>
                </div>
            </div>
        </div>
        <div class="chat">
            <div class="title">
                <h1>Tchat</h1>
            </div>
            <div class="tchat">

            </div>
            <div class="textarea">
                <form action="send.php?task=write" method="post" class="form_text">
                    <textarea name="message" id="message" cols="35" rows="2" placeholder="Votre message..."></textarea>
                    <div class="option_send">
                        <div class="option">
                            <div class="custom-radios">
                                <h3>Couleur du nom</h3>
                                <div>
                                    <input type="radio" id="color-1" name="color" value="#2ecc71">
                                    <label for="color-1">
                                    <span>
                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                    </span>
                                    </label>
                                </div>
                                
                                <div>
                                    <input type="radio" id="color-2" name="color" value="#3498db">
                                    <label for="color-2">
                                    <span>
                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                    </span>
                                    </label>
                                </div>
                                
                                <div>
                                    <input type="radio" id="color-3" name="color" value="#f1c40f">
                                    <label for="color-3">
                                    <span>
                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                    </span>
                                    </label>
                                </div>

                                <div>
                                    <input type="radio" id="color-4" name="color" value="#e74c3c">
                                    <label for="color-4">
                                    <span>
                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                    </span>
                                    </label>
                                </div>

                                <div>
                                    <input type="radio" id="color-5" name="color" value="#ef58e0">
                                    <label for="color-5">
                                    <span>
                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                    </span>
                                    </label>
                                </div>

                                <div>
                                    <input type="radio" id="color-6" name="color" value="#e74c3c">
                                    <label for="color-6">
                                    <span>
                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                    </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-7" name="color" value="#31e6ed">
                                    <label for="color-7">
                                    <span>
                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <span class="setting"><i class="fas fa-cog"></i></span>
                        <input type="submit" value="Envoyer" placeholder="Envoyer">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/ajax.js"></script>
    <script src="js/socialnetwork.js"></script>

</body>
</html>
