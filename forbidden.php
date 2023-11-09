<?php
$viewTitle = "Accès interdit!";
$error = "";
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
$viewContent = <<<HTML
    <br>
    <div class="loginForm">
    <h4>Vous devez vous connecter pour voir cette page</h4>
    <h5>$error</h5><br><br>
    <h2><a href='loginForm.php'>Connexion</a></h2>
    </div>
HTML;
$viewScript = <<<HTML
        <script defer>
            $("#addPhotoCmd").hide();
        </script>
        HTML;
include "views/master.php";
