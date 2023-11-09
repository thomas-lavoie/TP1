<?php
include 'php/sessionManager.php';
include_once "models/Users.php";

adminAccess();

$viewTitle = "Liste des usagers";
$list = UsersFile()->toArray();
$viewContent = "";

foreach ($list as $User) {
    $id = strval($User->id());
    $name = $User->name();
    $email = $User->Email();
    $avatar = $User->Avatar();
    $blocked = $User->Blocked();
    $type = $User->Type();

    $userCmd = "<i class='fa-solid fa-user blueCmd cmdIconVisible'></i>";
    if ($type == 1) {
        $userCmd = "<i class='fa-solid fa-user-gear blueCmd cmdIconVisible'></i>";
    }
    $blockedCmd = "<i class='fa-regular fa-circle greenCmd cmdIconVisible'></i>";
    if ($blocked) {
        $blockedCmd = "<i class='fa-solid fa-ban redCmd cmdIconVisible'></i>";
    }

    $UserHTML = <<<HTML
    <div class="UserRow" User_id="$id">
        <div class="UserContainer noselect">
            <div class="UserLayout">
                <div class="UserAvatar" style="background-image:url('$avatar')"></div>
                <div class="UserInfo">
                    <span class="UserName">$name</span>
                    <a href="mailto:$email" class="UserEmail" target="_blank" >$email</a>
                </div>
            </div>
            <div class="UserCommandPanel">
                <a href="updateUserType.php?id=$id">$userCmd</a>
                <a href="blockUser.php?id=$id">$blockedCmd</a>
                <a href="confirmDeleteUser?id=$id"><i class="fa-solid fa-user-slash goldenrodCmd cmdIconVisible"></i></a>
            </div>
        </div>
    </div>
    HTML;
    if ($_SESSION['currentUserId'] != $id) {
        $viewContent = $viewContent . $UserHTML;
    }
}

$viewScript = <<<HTML
    <script src='js/session.js'></script>
    <script defer>
        $("#addPhotoCmd").hide();
    </script>
HTML;

include "views/master.php";
