<?php
include 'php/sessionManager.php';
include "models/photos.php";
include "models/users.php";
$viewName="photoList";
userAccess();
$viewTitle = "Photos";
$list = PhotosFile()->toArray();
$viewContent = "<div class='photosLayout'>";
$ownerPhotosOnly = false;
if (isset($_GET["sort"])) {
    if ($_GET["sort"] == "date") {
        $list = Photo::sortByCreationDate($list);
    }
    if ($_GET["sort"] == "owners") {
        $list = Photo::sortByOwner($list);
    }
    if ($_GET["sort"] == "owned") {
        $ownerPhotosOnly = true;
    }
}

foreach ($list as $photo) {
    $id = strval($photo->id());
    $title = $photo->Title();
    $description = $photo->Description();
    $image = $photo->Image();
    $owner = UsersFile()->Get($photo->OwnerId());
    $ownerId = $owner->id();
    $ownerName = $owner->Name();
    $ownerAvatar = $owner->Avatar();
    $shared = $photo->Shared() == "true";
    $sharedIndicator = "";
    $editCmd = "";
    $visible = $shared;
    if (isset($_SESSION['validAdmin'])) {
        $visible = true;
            $editCmd = <<<HTML
                <a href="editPhotoForm.php?id=$id" class="cmdIconSmall fa fa-pencil" title="Editer $title"> </a>
                <a href="confirmDeletePhoto.php?id=$id"class="cmdIconSmall fa fa-trash" title="Effacer $title"> </a>
            HTML;
            if ($shared) {
                $sharedIndicator = <<<HTML
                    <div class="UserAvatarSmall transparentBackground" style="background-image:url('images/shared.png')" title="partagée"></div>
                HTML;
            } 
    } else {
        if (($photo->OwnerId() == (int)$_SESSION["currentUserId"])) {
            $visible = true;
            $editCmd = <<<HTML
                <a href="editPhotoForm.php?id=$id" class="cmdIconSmall fa fa-pencil" title="Editer $title"> </a>
                <a href="confirmDeletePhoto.php?id=$id"class="cmdIconSmall fa fa-trash" title="Effacer $title"> </a>
            HTML;
            if ($shared) {
                $sharedIndicator = <<<HTML
                    <div class="UserAvatarSmall transparentBackground" style="background-image:url('images/shared.png')" title="partagée"></div>
                HTML;
            } 
        }
    }
    if ($visible) {
        if ($ownerPhotosOnly) {
            if ($ownerId == $_SESSION["currentUserId"]) {
                $photoHTML = <<<HTML
                    <div class="photoLayout" photo_id="$id">
                        <div class="photoTitleContainer" title="$description">
                            <div class="photoTitle ellipsis">$title</div> $editCmd</div>
                        <a href="photoDetails.php?id=$id">
                            <div class="photoImage" style="background-image:url('$image')">
                                <div class="UserAvatarSmall transparentBackground" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
                                $sharedIndicator
                            </div>
                        </a>
                    </div>           
                HTML;
                $viewContent = $viewContent . $photoHTML;
            }
        } else {
            $photoHTML = <<<HTML
                <div class="photoLayout" photo_id="$id">
                    <div class="photoTitleContainer" title="$description">
                        <div class="photoTitle ellipsis">$title</div> $editCmd</div>
                    <a href="photoDetails.php?id=$id">
                        <div class="photoImage" style="background-image:url('$image')">
                            <div class="UserAvatarSmall transparentBackground" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
                            $sharedIndicator
                        </div>
                    </a>
                </div>           
            HTML;
            $viewContent = $viewContent . $photoHTML;
        }
    }
}
$viewContent = $viewContent . "</div>";

$viewScript = <<<HTML
    <script src='js/session.js'></script>
    <script defer>
        $("#addphotoCmd").hide();
    </script>
HTML;

include "views/master.php";
