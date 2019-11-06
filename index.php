<?php

use OoFile\File;
use OoFile\Upload;

require 'vendor/autoload.php';

$_FILES['login']['name']        = 'cities.txt';
$_FILES['login']['tmp_name']    = 'C:\Users\P3\Desktop\ALG\cities.txt';
$_FILES['login']['size']        = '50000';
$_FILES['login']['type']        = 'plain/text';
$_FILES['login']['errors']      = 0;


$filename = "login";

// set upload file
$up = new Upload($filename);

// set max size
// 1 = 1MB
echo '<pre>';
$up->setMaxSize(5)
->addAllowedTypes([
    'txt', 'plain/text'
]);

//$up->resetAllowedTypes(array('pdf'));

// $up->unique(); // override if name already exists

echo '<pre>';

echo '<pre>';

if($up->isValid()):
    $up->moveTo(__DIR__);
    echo 'yeah uploaded !';
else:
    print_r($up->errors());
endif;