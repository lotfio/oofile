<?php declare(strict_types=1);

use OoFile\File;
use Ouch\Reporter;

require 'vendor/autoload.php';

(new Reporter)->on();


echo conf("ahmed", "said");