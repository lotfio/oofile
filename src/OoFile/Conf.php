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

use OoFile\Exceptions\ConfigException; 
use OoFile\Exceptions\DirectoryException;
use OoFile\Exceptions\FileNameException; //3
use OoFile\Exceptions\FileNotFoundException; //4
use OoFile\Exceptions\FileTypeException; //5
use OoFile\Exceptions\FileException; //5

class Conf
{
    /**
     * config array
     *
     * @var array
     */
    public static $configArray = [

    ];

    /**
     * static call for config.
     */
    public static function __callStatic($name, $params)
    {
        return self::get($name, ...$params);
    }

    /**
     * add config file to config array.
     *
     * @param string $filename
     *
     * @return void
     */
    public static function load(string $filename)
    {
        if (!is_file($filename)) {
            throw new FileNotFoundException("config file $filename not found", 4);
        }

        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
            throw new FileTypeException("config file $filename must be of type php", 5);
        }

        $arrayFile = require $filename;

        if (!is_array($arrayFile)) {
            throw new FileException('config file must be a valid php array', 5);
        }

        $name = pathinfo($filename, PATHINFO_FILENAME);

        if (isset(self::$configArray[$name])) {
            
            return self::$configArray[$name] = array_merge(self::$configArray[$name], $arrayFile);
        }
            
        return self::$configArray = array_merge(self::$configArray, [$name => $arrayFile]);
    }

    /**
     * add a directory of config files
     *
     * @param string $path
     * @return void
     */
    public static function loadDir(string $path)
    {
        if (!is_dir($path)) {
            throw new DirectoryException('wrong directory path', 1);
        }
        
        $files = array_filter(scandir($path), function ($item) use ($path) {
            return !is_dir($path.$item);
        });

        if(count($files) < 1) {
            throw new FileNotFoundException("$path directory has no config files", 3);
        }

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'php') { // if php file

                if (!file_exists($path.DIRECTORY_SEPARATOR.$file)) {
                    throw new FileNotFoundException('cannot find file please provide an absolute path', 3);
                }

                $arrayFile = require $path.DIRECTORY_SEPARATOR.$file;

                if (!is_array($arrayFile)) {
                    throw new FileException('config file must be a valid php array', 5);
                }

                $name = pathinfo($file, PATHINFO_FILENAME);

                if(isset(self::$configArray[$name])) {
                    self::$configArray[$name] = array_merge(self::$configArray[$name], $arrayFile);
                }else{
                    self::$configArray        = array_merge(self::$configArray, [$name => $arrayFile]);
                }
            }
        }

        return self::$configArray;
    }

    /**
     * adding a config to config array
     *
     * @param  string $key
     * @param  array $conf
     * @return void
     */
    public function add(string $key, array $conf)
    {
        if(isset(self::$configArray[$key]))
        {
            return self::$configArray[$key] = array_merge(self::$configArray[$key], $conf);
        }

        return self::$configArray[$key] = $conf;
    }

    /**
     * get config from array or add.
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function get(string $config, string $key, string $value = null)
    {
       if(isset(self::$configArray[$config]) && isset(self::$configArray[$config][$key]))
        {
            return self::$configArray[$config][$key];
        }

        return self::$configArray[$config][$key] = $value;
    }

    /**
     * get all config method.
     *
     * @return array
     */
    public static function all() : array
    {
        return self::$configArray;
    }
}
