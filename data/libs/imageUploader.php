<?php

namespace MyApp;

class ImageUploader
{
    private $_imageFileName;
    private $_imageType;
    public function upload()
    {
        try {

            $this->_validateUpload();

            $ext = $this->_validateImageType();

            $savePath = $this->_saveImg($ext);

            $this->_createThumbnail($savePath);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        //redirect
        header('Location: http://' . $_SERVER['HTTP_HOST']);
    }

    private function _createThumbnail($savePath)
    {
        $imageSize = getimagesize($savePath);
        $width = $imageSize[0];
        $height = $imageSize[1];
        if ($width > THUMBNAIL_WIDTH) {
            $this->_createThumbnailMain($savePath, $width, $height);
        }
    }

    private function _createThumbnailMain($savePath, $width, $height)
    {
        switch ($this->_imageType) {
            case IMAGETYPE_GIF:
                $srcImage = imagecreatefromgif($savePath);
                break;
            case IMAGETYPE_JPEG:
                $srcImage = imagecreatefromjpeg($savePath);
                break;
            case IMAGETYPE_PNG:
                $srcImage = imagecreatefrompng($savePath);
                break;
        }
        $thumbHeight = round($height * THUMBNAIL_WIDTH / $width);
        $thumbImage = imagecreatetruecolor(THUMBNAIL_WIDTH, $thumbHeight);
        imagecopyresampled($thumbImage, $srcImage, 0, 0, 0, 0, THUMBNAIL_WIDTH, $thumbHeight, $width, $height);

        switch ($this->_imageType) {
            case IMAGETYPE_GIF:
                imagegif($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
                break;
            case IMAGETYPE_PNG:
                imagepng($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
                break;
        }
    }

    private function _saveImg($ext)
    {
        $this->_imageFileName = sprintf(
            '%s_%s.%s',
            time(),
            sha1(uniqid(mt_rand(), true)),
            $ext
        );
        $savePath = IMAGES_DIR . '/' . $this->_imageFileName;
        $res = move_uploaded_file($_FILES['image']['tmp_name'], $savePath);

        if ($res === false) {
            throw new \Exception("can not upload", 1);
        }
        return $savePath;
    }


    private function _validateUpload()
    {

        // var_dump($_FILES);
        // exit;

        if (!isset($_FILES['image']) || !isset($_FILES['image']['error'])) {
            throw new  \Exception("Error Processing Request", 1);
        }

        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_OK:
                return true;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \Exception("Too large", 1);

            default:
                throw new \Exception("Error Processing Request" . $_FILES['image']['error'], 1);
                break;
        }
    }

    private function _validateImageType()
    {
        $this->_imageType = exif_imagetype($_FILES['image']['tmp_name']);
        switch ($this->_imageType) {
            case IMAGETYPE_GIF:
                return 'gif';
            case IMAGETYPE_ICO:
                return 'ico';
            case IMAGETYPE_PNG:
                return 'png';
            case IMAGETYPE_JPEG:
                return 'jpg';
            default:
                throw new \Exception("PNG/JPEG/GIF", 1);
        }
    }

    public function getImages()
    {
        $images = [];
        $files = [];
        $imageDir = opendir(IMAGES_DIR);
        var_dump($imageDir);
        // while(false !=== ($file = readdir($imageDir))){
        // if ($file === '.' || $file === '..') {
        // continue;
        // }
        // var_dump($imageDir);
        // }
    }
}
