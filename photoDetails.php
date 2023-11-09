<?php
    require 'php/sessionManager.php';
    include "models/photos.php";
    include "models/users.php";
    $viewName="photoDetails";
    userAccess();
    $viewTitle = "Détails de la photo";
    $list = PhotosFile()->toArray();
    $viewContent = "<div class='photoLayout'>";

    if (isset($_GET["id"])) {
        $photo = PhotosFile()->get((int)$_GET["id"]);
    } else {
        redirect("forbidden.php");
    }
    if ($photo == null) {
        redirect("forbidden.php");
    }
    $id = strval($photo->id());
    $title = $photo->Title();
    $description = $photo->Description();

    $creationDate = $photo->CreationDate();
    date_default_timezone_set('America/New_York');
    $creationYear = date("Y", $creationDate);
    $creationMonth = Photo::monthToFrench(date("m", $creationDate));
    $creationDay = date("d", $creationDate);
    $creationWeekDay = Photo::weekDayToFrench(date("D", $creationDate));
    $creationTime = date("H:i:s", $creationDate);

    $image = $photo->Image();
    $owner = UsersFile()->Get($photo->OwnerId());
    $ownerId = $owner->id();
    $ownerName = $owner->Name();
    $ownerAvatar = $owner->Avatar();
    
    $photoHTML = <<<HTML
        <div class="photoDetailsLayout" photo_id="$id">
            <div class="photoDetailsOwner">
                <div class="UserAvatarSmall transparentBackground" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
                $ownerName
            </div>
            <div class="photoTitleContainer" title="$description">
                <div class="photoDetailsTitle ellipsis">$title</div>
            </div>
            <img class="photoDetailsLargeImage" src="$image">
            <!-- <div class="photoDetailsLargeImage" style="background-image:url('$image');"></div> -->
            <div class="photoDetailsDescription">$description</div>
            <div class="photoDetailsCreationDate">
                $creationWeekDay le $creationDay $creationMonth $creationYear @ $creationTime
            </div>
        </div>
    HTML;
    $viewContent = $viewContent . $photoHTML;

    $viewStyle = <<<HTML
        <style>
            #content {
                scroll-snap-type: none;
            }
        </style>
    HTML;

    include 'views/master.php';