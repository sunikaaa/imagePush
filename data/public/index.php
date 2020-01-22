<?php
// echo phpinfo();

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../libs/functions.php');
require_once(__DIR__ . '/../libs/imageUploader.php');
$uploader = new \MyApp\imageUploader();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploader->upload();
}

$images = $uploader->getImages();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/node_modules/vue/dist/vue.js"></script>
    <script src="/node_modules/axios/dist/axios.js"></script>
    <title>image upload</title>
</head>

<body>
    <div id="app">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?= h(MAX_FILE_SIZE); ?>">
            <input type="file" name="image" @change="selectFile">
            <input type="submit" value="upload" @click="upload">
        </form>
        <ul>
            <?php foreach ($images as $image) : ?>
                <li><a href="<?= h(basename(IMAGES_DIR)) . '/' . basename($image); ?>"><img src="<?= h($image); ?>"></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script src="js/main.js"></script>
</body>

</html>