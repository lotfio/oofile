<?php

namespace Tests\Unit;

use OoFile\Conf;
use OoFile\Exceptions\ConfigException;
use OoFile\Exceptions\DirectoryException;
use OoFile\Exceptions\FileTypeException;
use OoFile\Exceptions\FileNotFoundException;
use OoFile\Exceptions\FileException;
use PHPUnit\Framework\TestCase; //1

class ConfTest extends TestCase
{
    /**
     * test non existing config file.
     *
     * @return void
     */
    public function testConfFileNotExists()
    {
        $this->expectException(FileNotFoundException::class);
        Conf::load('file.php');
    }

    /**
     * test add wrong file to config.
     *
     * @return void
     */
    public function testNotPhpFile()
    {
        $this->expectException(FileTypeException::class);
        Conf::load(dirname(__DIR__).'/conf/app.ph');
    }

    /**
     * test not valida php array file.
     *
     * @return void
     */
    public function testNotValidPhpFile()
    {
        $this->expectException(FileException::class);
        Conf::load(dirname(__DIR__).'/conf/aapp.php');
    }

    /**
     * test not valid php array file.
     *
     * @return void
     */
    public function testConfigFileLoadedCorrectly()
    {
        Conf::load(dirname(__DIR__).'/conf/app.php');
        $this->assertSame('config_value', Conf::app('config_key'));
    }

    /**
     * test load config dir wrong dir.
     *
     * @return void
     */
    public function testLoadConfigWrongDir()
    {
        $this->expectException(DirectoryException::class);
        Conf::loadDir(dirname(__DIR__).'/conf/empt');
    }
    
    /**
     * test load config dir empty.
     *
     * @return void
     */
    public function testLoadValidConfigFiles()
    {
        $this->expectException(FileException::class);
        Conf::loadDir(dirname(__DIR__).'/conf/empty');
    }

}

