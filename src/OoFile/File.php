<?php namespace OoFile;

use OoFile\Exceptions\FileNameException;
use OoFile\Exceptions\FileModeException;
use OoFile\Exceptions\FilePermissionsException;

class File
{
    /**
     * file mods
     *
     * @var $mods array
     */
    private $mods = array(
        "r","r+",
        "w","w+",
        "a","a+",
        "x","x+",
        "c","c+",
        "e",
    );

    /**
     * create a file
     *
     * @method create
     * @param  string $file
     * @param  string $mode
     * @throws Exception
     * @return bool
     */
    public function create(string $file, string $mode = "w+") : bool
    {
        if(!is_string($file) || is_dir($file))
            throw new FileNameException("please provide valid filename string");

        if(!is_writable(getcwd()))
            throw new FilePermissionsException("you don't have permissions to create a file in this dir getcwd()");

        if(!in_array($mode, $this->mods))
            throw new FileModeException("$mode file mode not supported");

        $file = getcwd() . DIRECTORY_SEPARATOR . ltrim($file, "/");

        fopen($file, $mode);
        return file_exists($file);
    }

    /**
     * rename file method
     *
     * @param  string $old old file name
     * @param  string $new new file name
     * @return bool
     */
    public function rename(string $old, string $new) : bool
    {
        if(!is_string($old) || !is_string($new))
            throw new FileNameException("old and new file names must be valid strings");

        if(!file_exists($old))
            throw new \Exception("file $old not found", 404);

        return rename($old, $new);
    }

    /**
     * copy file method
     *
     * @param  string $old old file name
     * @param  string $new new file name
     * @return bool
     */
    public function copy($old, $new) : bool
    {
        if(!is_string($old) || !is_string($new))
            throw new FileNameException("old and new file names must be valid strings");

        if(!file_exists($old))
            throw new \Exception("file $old not found", 404);

        if(is_dir($new))
            throw new \Exception("the new file name is required ", 404);

        return copy($old, $new);
    }

    /**
     * move file method
     *
     * @param  string $old old file name
     * @param  string $new new file name
     * @return bool
     */
    public function move($file, $destination) : bool
    {
        if(!is_string($file) || !is_string($destination))
            throw new FileNameException("file name $file and distination $destination must be valid strings");

        if(!file_exists($file))
            throw new \Exception("file $file not found", 404);

        if(!is_dir($destination))
            throw new \Exception("destination $destination doesn't seem to be a valid directory", 404);

        $destination = trim($destination, "/") . DIRECTORY_SEPARATOR . $file;

        copy($file, $destination);

        return unlink($file);
    }

    /**
     * copy file method
     *
     * @param  string $old old file name
     * @param  string $new new file name
     * @return bool
     */
    public function delete($file) : bool
    {
        if(!is_string($file))
            throw new FileNameException("file name $file must be valid string");

        if(!file_exists($file))
            throw new \Exception("file $file not found", 404);

        return unlink($file);
    }
}