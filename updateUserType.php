<?php
    include 'php/sessionManager.php';
    include 'models/users.php';

    adminAccess();
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
    } else {
        redirect('forbidden.php');
    }

    UsersFile()->updateType($id);
    redirect('usersManagement.php');