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

use OoFile\Exceptions\ConfigException;
use OoFile\Exceptions\FileNotFoundException; //3

class DotConf
{
    /**
     * default config array
     *
     * @var array
     */
    public $defaultConf = array(
        "APP_NAME"      => "Silo",
        "APP_ENV"       => "dev",
        "APP_KEY"       => "",
        "APP_DEBUG"     => "true",
        "APP_URL"       => "http://localhost",
        "1"             => "SEPARATOR",
        "LOG"           => "true",
        "LOG_CHANNEL"   => "mlm",
        "2"             => "SEPARATOR",
        "DB_DRIVER"     => "mysqli",
        "DB_HOST"       => "127.0.0.1",
        "DB_PORT"       => "3306",
        "DB_NAME"       => "silo",
        "DB_USER"       => "root",
        "DB_PASS"       => "",
    );

    /**
     * initialize config file
     *
     * @return void
     */
    public function init(array $config = array())
    {
        $from    = (strpos(__DIR__, 'vendor') != 0) ? 'vendor' : 'src';
        $path    = explode($from, __DIR__)[0];
        $dotConf = $path . '.conf';

        $conf = (new File);
        $conf->create($dotConf);

        $confArray = empty($config) ? $this->defaultConf : $config;

        return $conf->write($dotConf, $this->buildConfString($confArray));
    }

    /**
     * build a config string from array
     *
     * @param array $confArray
     * @return string
     */
    public function buildConfString(array $confArray) : string
    {
        $confStr = '';

        $lengths = array_map('strlen', array_keys($confArray));
        $max     = max($lengths);
        
        foreach($confArray as $key => $value)
        {
            if($value == 'SEPARATOR')
            {
                $confStr .= "\n";
                continue;
            }
            $confStr .= $key . str_repeat(' ', ($max - strlen($key))) . ' : ' . $value . "\n";
        }

        return $confStr;
    }

    /**
     * read from .conf
     *
     * @param string $key
     * @return string
     */
    public static function read(string $key) : string
    {
        $from    = (strpos(__DIR__, 'vendor') != 0) ? 'vendor' : 'src';
        $path    = explode($from, __DIR__)[0];
        $dotConf = $path . '.conf';

        if(!file_exists($dotConf))
            throw new FileNotFoundException(".conf file not found", 4);
        
        $confArray = array();
        
        $handle = fopen($dotConf,'r');
        while(!feof($handle))
        {
            $line = fgets($handle);
            if(preg_match("/(.*)+\:{1}(.*)+/", $line))
            {
                $conf = explode(":", $line);
                $confArray[trim($conf[0])] = trim($conf[1]);
            }

        }
        
        if(!array_key_exists($key, $confArray))
            throw new ConfigException("$key not found in .conf file", 4);
        
        return $confArray[$key];
    }
}