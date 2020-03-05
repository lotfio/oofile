<?php

namespace OoFile;

/*
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

use OoFile\Exceptions\FileNameException;
use OoFile\Exceptions\DirectoryException;
use OoFile\Exceptions\DirectoryNotFoundException;

class Upload
{
    /**
     * number of files
     *
     * @var int
     */
    private $numberOfFiles;

    /**
     * file names
     *
     * @var string
     */
    private $names;

    /**
     * file extensions
     *
     * @var string
     */
    private $extensions;

    /**
     * temporary file names
     *
     * @var string
     */
    private $tempNames;

    /**
     * file upload errors
     *
     * @var int
     */
    private $errors;

    /**
     * upload names
     * file name after hashing
     *
     * @var string
     */
    private $upNames;

    /**
     * upload destinations
     *
     * @var string
     */
    private $destinations;

    /**
     * max file size is set to 8MB by default
     * 1MB = 1e+6 B
     * @var string
     */
    private $maxSize  = '8000000'; //8MB

    /**
     * allowed files types to upload
     *
     * @var array
     */
    private $allowedTypes = array(
        'png','jpg','jpeg','gif',
        'image/png','image/jpeg','image/gif'
    );

    /**
     * unique file upload
     * if file already exists do not upload
     * check by file content and name
     *
     * @var boolean
     */
    private $isUnique = array(

    );

    /**
     * validation errors
     *
     * @var array
     */
    private $validationErrors = array(

    );

    /**
     * initialize file for upload
     *
     * @param string $filename
     */
    public function __construct(string $filename, string $destination)
    {
        if(!isset($_FILES[$filename]))
            throw new FileNameException("$filename file is not valid posted file", 40);

        if(!is_dir($destination))
            throw new DirectoryNotFoundException("$destination is not a valid destination", 44);

        if(!is_writable($destination))
            throw new DirectoryException("$destination is not writable", 43);


        // number of files
        $this->numberOfFiles = count($this->unpackFiles($filename));

        $i = 0;
        foreach($this->unpackFiles($filename) as $file)
        {
            $this->names[$i]         = $file['name'];
            $extension              = explode('.', $file['name']);
            $this->extensions[$i]    = $extension[count($extension) - 1];

            $this->tempNames[$i]     = $file['temp'];

            $this->upNames[$i]       = SHA1($this->names[$i]) . '.' . $this->extensions[$i];
            $this->types[$i]         = $file['type'];
            $this->sizes[$i]         = $file['size'];
            $this->errors[$i]        = $file['error'];
            $this->destinations[$i]  = rtrim(rtrim($destination, '\\'),'/') . DIRECTORY_SEPARATOR;
            $this->isUnique[$i]      = FALSE;

            $i++;
        }
    }


    /**
     * organize files as arrays for multiple upload
     *
     * @param string $filename
     * @return array
     */
    private function unpackFiles(string $filename) : array
    {
        $files = $_FILES[$filename];
        $arr   = array();

        if(is_array($files['name']))
        {
            $i = 0;
            foreach($files['name'] as $file)
            {
                $arr[$i]['name']    = $files['name'][$i];
                $arr[$i]['temp']    = $files['tmp_name'][$i];
                $arr[$i]['type']    = $files['type'][$i];
                $arr[$i]['size']    = $files['size'][$i];
                $arr[$i]['error']   = $files['error'][$i];
                $i++;
            }

           return $arr;
        }
        return array($files);
    }

    /**
     * set max file size method
     *
     * @param  integer $size
     * @return integer
     */
    public function setMaxSize(int $size) : self
    {
        $this->maxSize = $size * 1000000;
        return $this;
    }

    /**
     * add to allowed types for upload
     *
     * @param  mixed $allowed
     * @return boolean
     */
    public function addAllowedTypes(...$allowed) : self
    {
        foreach($allowed as $key) // if make one dimension array to allow both single string and array parameters
        {
            if(is_array($key))
            {
                $this->allowedTypes = array_merge($this->allowedTypes, $key);
            }else{
                $this->allowedTypes[] = $key;
            }
        }

        return $this;
    }

    /**
     * reset allowed types to custom ignoring default allowed types
     *
     * @param  array $allowed
     * @return self
     */
    public function resetAllowedTypes(array $allowed) : self
    {
        $this->allowedTypes = $allowed;
        return $this;
    }

    /**
     * is valid method for validation
     *
     * @return boolean
     */
    public function isValid() : bool
    {
        // validate extension && file type
        // both .ext and MIME type validation
        for ($i = 0; $i < $this->numberOfFiles; $i++) {

            if (!in_array($this->extensions[$i], $this->allowedTypes) || !in_array($this->types[$i], $this->allowedTypes)) {
                $this->validationErrors[$i]['type'] = $this->types[$i] . ' file type is not allowed';
            }


            // validate file size
            if ($this->sizes[$i] > $this->maxSize) {
                $this->validationErrors[$i]['size'] = 'max file size is ' . $this->maxSize;
            }

            // validate no errors
            if ($this->errors[$i] != 0) {
                $this->validationErrors[$i]['error'] =  $this->errorTypes($this->errors[$i]);
            }

            // validate unique file content size and name
            if ($this->isUnique[$i] == 'strict') {
                $file = $this->destinations[$i] . $this->upNames[$i];
                if (file_exists($file)) {
                    if (sha1_file($file) == sha1_file($this->tempNames[$i]) && filesize($file) == filesize($this->tempNames[$i])) {
                        $this->validationErrors[$i]['name'] =  'file already exists (same size and content)';
                    }
                }
            }

                // validate unique file content size and name
            if ($this->isUnique[$i] == 'name') { // validate only unique name
                $file = $this->destinations[$i] . $this->upNames[$i];
                if (file_exists($file)) {
                    $this->validationErrors[$i]['name'] =  'file name already exists';
                }
            }
        }
        if(empty($this->validationErrors))
            return TRUE;

        return FALSE;
    }

    /**
     * upload error types
     *
     * @param  integer $code
     * @return string
     */
    public function errorTypes(int $code) : string
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;
            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }

    /**
     * errors method
     * returns all validation errors
     *
     * @return array
     */
    public function errors() : array
    {
        return $this->validationErrors;
    }

    /**
     * unique upload
     * if strict check both file content and name
     * else only file name
     *
     * @param boolean $strict
     * @return self
     */
    public function unique(bool $strict = FALSE) : self
    {
        for($i = 0; $i < $this->numberOfFiles; $i++)
        {
            $this->isUnique[$i] = ($strict === TRUE) ? 'strict' : 'name';
        }
        return $this;
    }

    /**
     * upload method
     *
     * @return boolean
     */
    public function proceed() : array
    {
        $uploaded = array();

        for($i = 0; $i < $this->numberOfFiles; $i++)
        {
            // if not unique allow duplicate with new name
            if($this->isUnique[$i] === FALSE)
                $this->upNames[$i]    = SHA1(bin2hex(random_bytes(10)) . substr(uniqid(), -7, 5) . $this->upNames[$i]) . '.' . $this->extensions[$i];

            $uploaded[$this->upNames[$i]] =  move_uploaded_file($this->tempNames[$i], $this->destinations[$i] . $this->upNames[$i]);
        }

        return $uploaded;
    }
}