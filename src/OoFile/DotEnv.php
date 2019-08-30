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
use OoFile\Exceptions\DirectoryException;
use OoFile\Exceptions\FileNotFoundException; //3

class DotEnv
{
    /**
     * default env array
     *
     * @var array
     */
    private $defaultEnv = array(
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
     * initialize env file
     * @param $envArray env variables to be added or modified
     * @param &$path reference to where env file is created
     * 
     * @return void
     */
    public function init(array $envArray = array(), &$path = NULL)
    {
        $from    = (strpos(__DIR__, 'vendor') != 0) ? 'vendor' : 'src';
        $path    = explode($from, __DIR__)[0];
        $dotEnv  = $path . '.env';

        $file = (new File);
        $file->create($dotEnv);

        $envArray = array_merge($this->defaultEnv, $envArray);

        return $file->write($dotEnv, $this->buildEnvString($envArray));
    }

    /**
     * build a env string from array
     *
     * @param array $envArray
     * @return string
     */
    private function buildEnvString(array $envArray) : string
    {
        $envStr = '';

        $lengths = array_map('strlen', array_keys($envArray));
        $max     = max($lengths);
        
        foreach($envArray as $key => $value)
        {
            if($value == 'SEPARATOR')
            {
                $envStr .= "\n";
                continue;
            }
            $envStr .= $key . str_repeat(' ', ($max - strlen($key))) . ' : ' . $value . "\n";
        }

        return $envStr;
    }

    /**
     * read from .env
     *
     * @param string $key
     * @return string
     */
    public static function read(string $key, $default = NULL)
    {
        $from    = (strpos(__DIR__, 'vendor') != 0) ? 'vendor' : 'src';
        $path    = explode($from, __DIR__)[0];
        $dotEnv  = $path . '.env';

        if(!file_exists($dotEnv))
            throw new FileNotFoundException(".env file not found", 4);
        
        $envArray = array();
        
        $handle = fopen($dotEnv,'r');
        while(!feof($handle))
        {
            $line = fgets($handle);
            if(preg_match("/[\w\s]+:{1}[\w\s]+/", $line))
            {
                $env = explode(":", $line);
                $envArray[trim($env[0])] = trim($env[1]);
            }
        }
        
        if(!array_key_exists($key, $envArray) || strlen($envArray[$key]) == 0)
            return $envArray[$key] = $default;
        
        return $envArray[$key];
    }

    /**
     * delete dot env file
     * @param &$path reference from where .env file as deleted 
     * 
     * @return void
     */
    public function deleteEnvFile(&$path = NULL)
    {
        $from    = (strpos(__DIR__, 'vendor') != 0) ? 'vendor' : 'src';
        $path    = explode($from, __DIR__)[0];
        $dotEnv  = $path . '.env';

        if(!file_exists($dotEnv))
            throw new FileNotFoundException("$dotEnv file not found");
        
        if(!is_writable($path))
            throw new DirectoryException("can not delete .env file $path is not writable");
        
        return unlink($dotEnv);
    }
}