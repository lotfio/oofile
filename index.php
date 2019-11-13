<?php

use OoFile\File;
use OoFile\Upload;

require 'vendor/autoload.php';


if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    $destination = __DIR__;
    // set upload file
    $up = new Upload('images', $destination);


    // set max size
    // 1 = 1MB
    echo '<pre>';
    $up->setMaxSize(5)
    ->addAllowedTypes([
        'txt', 'text/plain'
    ]);

    //$up->resetAllowedTypes(array('pdf'));

    //$up->unique(TRUE); // do not upload if already exists

    echo '<pre>';

    echo '<pre>';

    if($up->isValid()):
        $up->proceed();
        echo 'yeah uploaded !';
    else:
        print_r($up->errors());
    endif;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="" method="POST" enctype="multipart/form-data">

    <input type="file" name="images[]" multiple>

    <input type="submit" value="upload">
</form>

</body>
</html>