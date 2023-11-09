<?php

include_once 'models/records.php';
include_once 'php/guid.php';
include_once 'php/formUtilities.php';
include_once 'php/imageFiles.php';

const PhotosFile = "data/photos.data";
const photosPath = "data/images/photos/";

class Photo extends Record
{
    private $ownerId;       // Id du propriétaire de la photo
    private $title;         // Titre de la photo
    private $description;   // Description de la photo
    private $creationDate;  // Date de création
    private $shared;        // Indicateur de partage ("true" ou "false")
    private $image;         // Url relatif de l'image

    public function __construct($recordData)
    {
        $this->creationDate = time();
        $this->shared = false;
        parent::__construct($recordData);
        date_default_timezone_set("America/New_York");
    }
    public function setOwnerId($ownerId)
    {
        $id = (int) $ownerId;
        if ($id > 0)
            $this->ownerId = $id;
    }
    public function setTitle($title)
    {
        if (is_string($title))
            $this->title = $title;
    }
    public function setDescription($description)
    {
        if (is_string($description))
            $this->description = $description;
    }
    public function setShared($shared)
    {
        if ($shared == "on")
            $this->shared = "true";
        else
            $this->shared = $shared == "true" ? "true": "false";
    }
    public function setImage($image)
    {
        if (is_string($image))
            $this->image = $image;
    }
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }
    public function OwnerId()
    {
        return $this->ownerId;
    }
    public function Title()
    {
        return $this->title;
    }
    public function Description()
    {
        return $this->description;
    }
    public function CreationDate()
    {
        return $this->creationDate;
    }
    public function Shared()
    {
        return $this->shared;
    }
    public function Image()
    {
        return $this->image;
    }
    public static function compare($photo_a, $photo_b)
    {
        $time_a = (int)$photo_a->CreationDate();
        $time_b = (int)$photo_b->CreationDate();
        if ($time_a == $time_b) return 0;
        if ($time_a > $time_b) return -1;
        return 1;
    }
    public static function sortByCreationDate($list) {
        usort($list, 'Photo::compare');
        return $list;
    }
    public static function sortByOwner($list) {
        usort($list, function ($photo_a, $photo_b) {
            $nom_a = (UsersFile()->Get($photo_a->OwnerId()))->Name();
            $nom_b = (UsersFile()->Get($photo_b->OwnerId()))->Name();
            if ($nom_a === null && $nom_b === null) {
                return 0; // Both users are null, consider them equal.
            }
            if ($nom_a === null) {
                return 1; // User A is null, so B comes first.
            }
            if ($nom_b === null) {
                return -1; // User B is null, so A comes first.
            }
            return strcmp($nom_a, $nom_b);
        });
        return $list;
    }
    static function keyCompare($photo_a, $photo_b)
    {
        return 1;
    }
    public static function weekDayToFrench($day) {
        switch ($day) {
            case "Sun":
                $day = "Dimanche";
                break;
            case "Mon":
                $day = "Lundi";
                break;
            case "Tue":
                $day = "Mardi";
                break;
            case "Wed":
                $day = "Mercredi";
                break;
            case "Thu":
                $day = "Jeudi";
                break;
            case "Fri":
                $day = "Vendredi";
                break;
            case "Sat":
                $day = "Samedi";
                break;
        }
        return $day;
    }
    public static function monthToFrench($month) {
        switch ($month) {
            case "01":
                $month = "janvier";
                break;
            case "02":
                $month = "février";
                break;
            case "03":
                $month = "mars";
                break;
            case "04":
                $month = "avril";
                break;
            case "05":
                $month = "mai";
                break;
            case "06":
                $month = "juin";
                break;
            case "07":
                $month = "juillet";
                break;
            case "08":
                $month = "aout";
                break;
            case "09":
                $month = "septembre";
                break;
            case "10":
                $month = "octobre";
                break;
            case "11":
                $month = "novembre";
                break;
            case "12":
                $month = "décembre";
                break;
        }
        return $month;
    }
}

class PhotosFile extends Records
{
    public function add($photo)
    {
        $photo->setImage(saveImage(photosPath, $photo->Image()));
        parent::add($photo);
    }
    public function update($photo)
    {
        $photoToUpdate = $this->get($photo->Id());
        if ($photoToUpdate != null) {
            $photo->setImage(saveImage(photosPath, $photo->Image(), $photoToUpdate->Image()));
            parent::update($photo);
        }
    }
    public function remove($id)
    {
        $photoToRemove = $this->get($id);
        if ($photoToRemove != null) {
            unlink($photoToRemove->image());
            return parent::remove($id);
        }
        return false;
    }
}

function PhotosFile()
{
    return new PhotosFile(PhotosFile);
}