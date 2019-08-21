<?php

use OoFile\File;

require 'vendor/autoload.php';

try{

$file = new File;
$file->rename("azaz");

}catch(\Exception $e)
{
    echo(get_class($e));
    echo "\n";
    die($e->getMessage());
}