<?php
//namespace App\Http\Services;
//
//
//class ImageToolsService
//{
//    protected $image;
//    protected $exclusiveDirectory;
//    protected $imageDirectory;
//    protected $imageName;
//    protected $imageFormat;
//    protected $finaleImageDirectory;
//    protected $finaleImageFormat;
//
//
//    public function setImage($image)
//    {
//        $this->image=$image;
//    }
//    public function getExclusiveDirectory()
//        {
//            return $this->exclusiveDirectory;
//        }
//public function setExclusiveDirectory($exclusiveDirectory)
//{
//    $this->exclusiveDirectory=trim($exclusiveDirectory,'/\\');
//}
//
//
//public function getImageDirectory()
//{
//    return $this->imageDirectory;
//}
//
//
//    public function setImageDirectory($imageDirectory)
//    {
//        $this->imageDirectory=trim($imageDirectory,'/\\');
//    }
//
//
//    public function getImageName()
//    {
//        return $this->imageName;
//    }
//
//    public function setImageName($imageName)
//    {
//        $this->imageName=$imageName;
//    }
//
//public function setCurrentImageName()
//{
//    return !empty($this->image)? $this->setImageName(pathinfo($this->image->getClientOriginalName(),PATHINFO_FILENAME)):false;
//}
//
//
//public function getImageFormat()
//{
//    return $this->imageFormat;
//}
//
//    public function setImageFormat($imageFormat)
//    {
//        $this->imageFormat=$imageFormat;
//    }
//public function getFinaleImageDirectory()
//{
//    return $this->finaleImageDirectory;
//}
//public function setFinaleImageDirectory($finaleImageDirectory)
//{
//    $this->finaleImageDirectory=$finaleImageDirectory;
//}
//
//
//
//
//
//    public function getFinaleImageName()
//    {
//        return $this->finaleImageName;
//    }
//    public function setFinaleImageName($finaleImageName)
//    {
//        $this->finaleImageName=$finaleImageName;
//    }
//
//
//
//
//
//
//
//
//
//}
//
