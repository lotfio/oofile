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

use OoFile\Exceptions\DirectoryException;
use OoFile\Exceptions\FileNotFoundException; //3

class DotEnv
{
    /**
     * env array
     *
     * @var array
     */
    private $envArray = array(

    );

    /**
     * env path
     *
     * @var string
     */
    private $path;

    /**
     * init
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = rtrim($path, "/\\") . DIRECTORY_SEPARATOR;
    }

    /**
     * initialize env file.
     *
     * @param string $path where to initialize .env
     *
     * @return bool
     */
    public function initialize() : bool
    {   
        $exEnv      = Conf::env('filename', '.env.example');
        $example    = $this->path . $exEnv;
        $env        = $this->path . '.env';

        if (!file_exists($example))
            throw new FileNotFoundException(" example .env file $example not found");

        $file = (new File());
        return $file->copy($example, $env);
    }

    /**
     * load env file
     *
     * @return array
     */
    public function load() : array
    {
        if (!file_exists($this->path))
            throw new FileNotFoundException(" example .env file not found");

        $handle = fopen($this->path . '.env', 'r');

        while (!feof($handle)) {
            $line = fgets($handle);
            if (preg_match("/[\w\s]+:{1}[\w\s]+/", $line)) {
                $env = explode(':', $line);
                $this->envArray[trim($env[0])] = trim($env[1]);
            }
        }
        return $this->envArray;
    }

    /**
     * update env key
     *
     * @param  string $key
     * @param  string $value
     * @return array
     */
    public function changeValue(string $key, string $value) : array
    {
        if(!isset($this->envArray[strtoupper($key)]))
            die("env key doesnot exists");

        $this->envArray[strtoupper($key)] = $value;

        return $this->envArray;
    }

    /**
     * update env file.
     *
     * @return int
     */
    public function update() : int
    {
        $str = '';

        $longest = max(array_map(function($k){ return strlen($k);}, array_keys($this->envArray)));
        $longest = $longest + 2; // extra whte spaces

        foreach($this->envArray as $key => $value)
        {
            $str .= $key . str_repeat(' ', $longest - strlen($key)) . ":  " . $value . "\n";
        }

        return file_put_contents($this->path . '.env', $str);
    }

    /**
     * read from env
     *
     * @return ?string
     */
    public function read(string $key, string $default = NULL) : ?string
    {
        if (isset($this->envArray[strtoupper($key)]))
            return $this->envArray[strtoupper($key)];
        
        return $this->envArray[strtoupper($key)] = $default;
    }

    /**
     * delete dot env file.
     * 
     * @return bool
     */
    public function delete() : bool
    {
        $env = $this->path . '.env';

        if (!file_exists($dotEnv)) {
            throw new FileNotFoundException("$env file not found");
        }
        if (!is_writable($this->path)) {
            throw new DirectoryException("can not delete .env file $this->path is not writable");
        }

        return unlink($env);
    }
}
