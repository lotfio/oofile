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

class Conf
{
    /**
     * config array
     * all config arrays siuld be merged to this array
     *
     * @var array
     */
    private $config = array(

    );

    /**
     * add to config array
     *
     * @param string $path
     * @return void
     */
    public function add(string $path)
    {

        if(!is_dir($path) && !is_file($path)) throw new \Exception("must be file a dir", 1);

        if(is_dir($path))
        {

            $files = array_filter(scandir($path), function($item) use ($path) {
                return !is_dir($path . $item);
            });

            foreach($files as $file)
            {
                if(pathinfo($file, PATHINFO_EXTENSION) == "php") // if php file
                {
                    if(!file_exists($path . DIRECTORY_SEPARATOR . $file)) throw new \Exception("cannot find file pelase provide an absolute path", 1);
                    $arrayFile = require $path . DIRECTORY_SEPARATOR . $file;
                    if(is_array($arrayFile)) $this->config = array_merge($this->config, $arrayFile);
                }

            }
        }

        if(is_file($path))
        {
            if(pathinfo($path, PATHINFO_EXTENSION) == "php")
            {
                $arrayFile = require $path;
                if(is_array($arrayFile)) $this->config = array_merge($this->config, $arrayFile);
            }
        }
    }

    /**
     * get config from array
     *
     * @param  string $key
     * @return mixed
     */
    public function get(string $key, $value = NULL)
    {
        if(is_null($value))
        {
            if(!$this->exists($key))
            throw new \Exception("$key doesn't exists", 1);

            return $this->config[$key];
        }

        return $this->config[$key] = $value;
    }

    /**
     * check if config exists
     *
     * @param  string $key
     * @return boolean
     */
    public function exists(string $key) : bool
    {
        return array_key_exists($key, $this->config);
    }
}

