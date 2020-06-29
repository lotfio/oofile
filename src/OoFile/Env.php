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
use OoFile\Exceptions\FileNotFoundException;
use OoFile\Exceptions\EnvException;

class Env
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
     * file manager
     *
     * @var File
     */
    private $fileManager;

    /**
     * init
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path         = rtrim($path, "/\\") . DIRECTORY_SEPARATOR;
        $this->fileManager  = new File;
    }

    /**
     * initialize env file.
     *
     * @param string $path where to initialize .env
     *
     * @return bool
     */
    public function init() : bool
    {
        $example    = $this->path . '.env.example';
        $env        = $this->path . '.env';

        if (!file_exists($example))
            throw new FileNotFoundException(" example .env file $example not found");

        return $this->fileManager->copy($example, $env);
    }

    /**
     * load env file
     *
     * @return array
     */
    public function load() : array
    {
        if (!is_file($this->path . '.env'))
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
     * update env file. after changing values (uses envArray)
     *
     * @return int
     */
    public function update() : bool
    {
        $str = '';

        $longest = array_map(function($k){ return strlen($k);}, array_keys($this->envArray));
        $longest = (count($longest) > 0) ? max($longest) + 2 : 0; // extra white spaces

        foreach($this->envArray as $key => $value)
        {
            $str .= $key . str_repeat(' ', $longest - strlen($key)) . ":  " . $value . "\n";
        }

        return $this->fileManager->write($this->path . '.env', $str);
    }

    /**
     * delete dot env file.
     *
     * @return bool
     */
    public function delete() : bool
    {
        $env = $this->path . '.env';

        if (!file_exists($env)) {
            throw new FileNotFoundException("$env file not found");
        }
        if (!is_writable($this->path)) {
            throw new DirectoryException("can not delete .env file $this->path is not writable");
        }

        return $this->fileManager->delete($env);
    }

    /**
     * update env key
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public function set(string $key, string $value) : string
    {
        if(!isset($this->envArray[strtoupper($key)]))
            throw new EnvException("env key does not exists", 4);

        return $this->envArray[strtoupper($key)] = $value;
    }

    /**
     * read from env
     *
     * @return ?string
     */
    public function get(string $key, string $default = NULL) : ?string
    {
        if (isset($this->envArray[strtoupper($key)]))
            return $this->envArray[strtoupper($key)];

        return $this->envArray[strtoupper($key)] = $default;
    }
}