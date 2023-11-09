<?php
    include("php/sessionManager.php");
    include_once "models/Users.php";
    $viewTitle = "Supprimer un usager";

    adminAccess();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    }

    $list = UsersFile()->toArray();
    foreach ($list as $user) {
        $userId = $user->id();
        if ($userId == $id) {
            $userToDelete = $user;
            break;
        }
    }

    $avatar = $userToDelete->Avatar();
    $name = $userToDelete->name();

    if ($userToDelete == null) {
        redirect("forbidden.php");
    }

    $viewContent = <<<HTML
        <div class="content loginForm">
            <br>
            <h3>Voulez vous vraiment effacer l'usager suivant?</h3>
            <div class="form">
                <div class="UserAvatar" style="background-image:url('$avatar');margin:0 auto;"></div>
                <h4>$name</h4>
                <br>
                <a href='deleteUser.php?id=$id'><button class='form-control btn-danger'>Effacer l'usager</button></a>
                <br>
                <a href="usersManagement.php" class="form-control btn-secondary">Annuler</a>
            </div>
        </div>
    HTML;
    include "views/master.php";