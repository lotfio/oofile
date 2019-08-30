<?php namespace Tests\Unit;

use OoFile\File;
use PHPUnit\Framework\TestCase;
use OoFile\Exceptions\FileNameException; //1
use OoFile\Exceptions\FileModeException; //2
use OoFile\Exceptions\FileNotFoundException; //3
use OoFile\Exceptions\FilePermissionsException; //4
use OoFile\Exceptions\DirectoryNotFoundException; //4

class FileTest extends TestCase
{
    protected $file;

    public function setUp() : void
    {
        $this->file = new File;
    }

    public function testCreateMethodWithWrongName()
    {
        $this->expectException(FileNameException::class);
        $this->file->create("tests/temp/");
    }

    public function testCreateFileWithModeNotExist()
    {
        $this->expectException(FileModeException::class);
        $create = $this->file->create("tests/temp/test.txt", "any");
    }

    public function testCreateFile()
    {
        $create = $this->file->create("tests/temp/test.txt");
        $this->assertTrue($create);
    }

    public function testRenameFileNotExists()
    {
        $this->expectException(FileNotFoundException::class);
        $rename = $this->file->rename("tests/temp/test5.txt", "tests/temp/test2.txt");
    }

    public function testRenameFile()
    {
        $rename = $this->file->rename("tests/temp/test.txt", "tests/temp/test2.txt");
        $this->assertTrue($rename);
    }

    public function testCopyFileNotExists()
    {
        $this->expectException(FileNotFoundException::class);
        $rename = $this->file->copy("tests/temp/test5.txt", "tests/temp/test2.txt");
    }

    public function testCopyFile()
    {
        $copy = $this->file->copy("tests/temp/test2.txt", "tests/temp/test2-copy.txt");
        $this->assertTrue($copy);
    }

    public function testMoveFileNotExists()
    {
        $this->expectException(FileNotFoundException::class);
        $move = $this->file->move("tests/temp/test5.txt", "tests/");
    }

    public function testMoveFileNoDirectory()
    {
        $this->expectException(DirectoryNotFoundException::class);
        $move = $this->file->move("tests/temp/test2.txt", "tests/wrongDir");
    }

    public function testMoveFile()
    {
        $move = $this->file->move("tests/temp/test2.txt", "tests/temp/temp2/");
        $this->assertTrue($move);
    }

    public function testFileSizeMethod()
    {
        $size = $this->file->size('tests/temp/temp2/test2.txt');
        $this->assertIsInt($size);
    }

   public function testWriteMethod()
    {
        $this->file->write('tests/temp/temp2/test2.txt', "123456789+");
        $size = $this->file->size('tests/temp/temp2/test2.txt');
        $this->assertEquals(10, $size);
    }

    public function testDeleteNotExistsFile()
    {
        $this->expectException(FileNotFoundException::class);
        $move = $this->file->delete("tests/temp/test5.txt");
    }

    public function testDeleteFile()
    {
        $delete  = $this->file->delete("tests/temp/temp2/test2.txt");
        $delete2 = $this->file->delete("tests/temp/test2-copy.txt");
        $this->assertTrue($delete);
        $this->assertTrue($delete2);
    }
}