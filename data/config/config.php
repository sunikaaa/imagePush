<?php
ini_set('display_errors', 1);

const MAX_FILE_SIZE = 1 * 1024 * 1024;
const THUMBNAIL_WIDTH = 400;
const IMAGES_DIR =  __DIR__ . '/../images';
const THUMBNAIL_DIR = __DIR__ . '/../public/thumbs';

if (!function_exists('imagecreatetruecolor')) {
    echo 'GD not installed';
    exit;
}
