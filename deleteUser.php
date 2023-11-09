<?php
    include 'php/sessionManager.php';
    include 'models/users.php';
    include 'models/photos.php';

    adminAccess();
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
    } else {
        redirect('forbidden.php');
    }

    do {
        $photos = PhotosFile()->toArray();
        $oneDeleted = false;
        foreach ($photos as $photo) {
            if ($photo->OwnerId() == $id) {
                $oneDeleted = true;
                PhotosFile()->remove($photo->Id());
                break;
            }
        }
    } while ($oneDeleted);

    UsersFile()->remove($id);
    redirect('usersManagement.php');