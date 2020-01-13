<?php

namespace MyApp;

class ImageUploader
{

    public function upload()
    {
        try {

            $this->_validateUpload();

            $ext = $this->_validateImageType();

            $this->_saveImg($ext);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        //redirect
        header('Location: http://' . $_SERVER['HTTP_HOST']);
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
        $imageType = exif_imagetype($_FILES['image']['tmp_name']);
        switch ($imageType) {
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
}
