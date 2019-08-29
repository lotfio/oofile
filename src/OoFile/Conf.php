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

use Exception;
use OoFile\Exceptions\FileNameException; //1
use OoFile\Exceptions\FileNotFoundException; //3
use OoFile\Exceptions\ConfigException; //3

class Conf
{
    /**
     * config array
     * all config arrays should be merged to this array
     *
     * @var array
     */
    public static $configArray = array(

    );

    /**
     * add to config array
     *
     * @param string $path
     * @return void
     */
    public static function add(string $path)
    {

        if(!is_dir($path) && !is_file($path)) throw new FileNameException("wrong file or directory path", 1);

        if(is_dir($path))
        {

            $files = array_filter(scandir($path), function($item) use ($path) {
                return !is_dir($path . $item);
            });

            foreach($files as $file)
            {
                if(pathinfo($file, PATHINFO_EXTENSION) == "php") // if php file
                {
                    if(!file_exists($path . DIRECTORY_SEPARATOR . $file)) throw new FileNotFoundException("cannot find file pelase provide an absolute path", 3);
                    $arrayFile = require $path . DIRECTORY_SEPARATOR . $file;

                    $file = pathinfo($file, PATHINFO_FILENAME);
                    if(is_array($arrayFile)) self::$configArray[$file] = $arrayFile;
                }

            }
        }

        if(is_file($path))
        {
            if(pathinfo($path, PATHINFO_EXTENSION) == "php")
            {
                $arrayFile = require $path;
                $path = pathinfo($path, PATHINFO_FILENAME);
                if(is_array($arrayFile)) self::$configArray[$path] = $arrayFile;
            }
        }
    }

    /**
     *
     */
    public static function __callStatic($name, $params)
    {
        if(!array_key_exists($name, self::$configArray))
            throw new Exception(" $name config method doesn't exists", 4);

        return self::get($name, ...$params);
    }

    /**
     * get config from array
     *
     * @param  string $key
     * @return mixed
     */
    public static function get(string $config, string $key, $value = NULL)
    {
        if(is_null($value))
        {
            if(!array_key_exists($key, self::$configArray[$config]))
                throw new ConfigException("config key $key doesn't exist", 4);

            return self::$configArray[$config][$key];
        }

        return self::$configArray[$config][$key] = $value;
    }

    /**
     * add to an array
     * add arrays or string values
     */
    public static function append(string $config, string $key, $value)
    {
        if(!array_key_exists($config, self::$configArray))
                throw new ConfigException("config key $config doesn't exist", 4);

        if(!array_key_exists($key, self::$configArray[$config])) // if key doesn't exists add it and append to it
            self::$configArray[$config][$key] = array();

        if(is_string(self::$configArray[$config][$key])) // if string
        {
            self::$configArray[$config][$key] = array(
                self::$configArray[$config][$key],
            );
        }

        if(is_array($value))
        {
            return self::$configArray[$config][$key] = array_merge( self::$configArray[$config][$key], $value);
        }

        return self::$configArray[$config][$key][] = $value;
    }


    /*
     * TODO refactor this
     *
     * @param  string $key
     * @return boolean
     *
    public static function exists(string $key) : bool
    {
        return array_key_exists($key, self::$config);
    }*/
}

