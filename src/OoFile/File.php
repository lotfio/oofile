<?php namespace OoFile;

/**
 * OoFile       PHP file manipulation package
 *
 * @author      Lotfio Lakehal <contact@lotfio.net>
 * @copyright   2019 Lotfio Lakehal
 * @license     MIT
 *
 * @link        https://github.com/lotfio/oofile
 *
 * Copyright (c) 2019 lotfio lakehal
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

use OoFile\Exceptions\FileNameException; //1
use OoFile\Exceptions\FileModeException; //2
use OoFile\Exceptions\FileNotFoundException; //3
use OoFile\Exceptions\FilePermissionsException; //4
use OoFile\Exceptions\DirectoryNotFoundException; //5

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
            throw new FileNameException("please provide valid filename string", 1);

        if(!is_writable(getcwd()))
            throw new FilePermissionsException("you don't have permissions to create a file in this dir", 4);

        if(!in_array($mode, $this->mods))
            throw new FileModeException("$mode file mode not supported", 2);

        fopen($file, $mode);
        return file_exists($file);
    }

    /**
     * get file size
     *
     * @param string $file
     * @return integer
     */
    public function size(string $file) : int
    {
        if(!file_exists($file))
            throw new NotFoundException("file $file not found", 4);
            
        return filesize($file);
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
            throw new FileNameException("old and new file names must be valid strings", 1);

        if(!file_exists($old))
            throw new FileNotFoundException("file $old not found", 3);

        return rename($old, $new);
    }

    /**
     * copy file method
     *
     * @param  string $old old file name
     * @param  string $new new file name
     * @return bool
     */
    public function copy(string $old, string $new) : bool
    {
        if(!is_string($old) || !is_string($new) || is_dir($old) || is_dir($new))
            throw new FileNameException("old and new file names must be valid strings", 1);

        if(!file_exists($old))
            throw new FileNotFoundException("file $old not found", 3);

        return copy($old, $new);
    }

    /**
     * move file method
     *
     * @param  string $old old file name
     * @param  string $new new file name
     * @return bool
     */
    public function move(string $file, string $destination) : bool
    {
        if(!is_string($file) || !is_string($destination))
            throw new FileNameException("file name $file and destination $destination must be valid strings", 1);

        if(!file_exists($file))
            throw new FileNotFoundException("file $file not found", 4);

        if(!is_dir($destination))
            throw new DirectoryNotFoundException("destination $destination doesn't seem to be a valid directory", 4);

        $destination = trim($destination, "/") . DIRECTORY_SEPARATOR . pathinfo($file, PATHINFO_FILENAME) .".". pathinfo($file, PATHINFO_EXTENSION);

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
    public function delete(string $file) : bool
    {
        if(!is_string($file))
            throw new FileNameException("file name $file must be valid string", 1);

        if(!file_exists($file))
            throw new FileNotFoundException("file $file not found", 4);

        return unlink($file);
    }
}