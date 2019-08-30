<?php namespace Tests\Unit;

use OoFile\Conf;
use OoFile\Exceptions\ConfigException;
use PHPUnit\Framework\TestCase;
use OoFile\Exceptions\FileNameException; //1

class ConfTest extends TestCase
{
    /**
     * test add wrong dir to config
     *
     * @return void
     */
    public function testConfAddWrongDirectory()
    {
        $this->expectException(FileNameException::class);
        Conf::add('../conf');
    }

    /**
     * test add wrong file to config
     *
     * @return void
     */
    public function testConfAddWrongFile()
    {
        $this->expectException(FileNameException::class);
        Conf::add('file.php');
    }

    /**
     * adding a directory of config files
     *
     * @return void
     */
    public function testAddConfDirectory()
    {
        Conf::add(dirname(__DIR__).'/conf');
        $this->assertIsArray(Conf::all());
        $this->assertSame('config_value', Conf::app('config_key'));
    }

    /**
     * adding only a file to the config
     *
     * @return void
     */
    public function testAddConfFile()
    {
        Conf::add(dirname(__DIR__).'/conf/app.php');
        $this->assertIsArray(Conf::all());
        $this->assertSame('config_value', Conf::app('config_key'));
    }

    /**
     * static call for non existing config file
     *
     * @return void
     */
    public function testCallNonExistingConfig()
    {
        $this->expectException(ConfigException::class);
        Conf::JustDummyMethod('param');
    }

    /**
     * test call non exiting config file
     *
     * @return void
     */
    public function testGetMethodNonExistingConfig()
    {
        $this->expectException(ConfigException::class);
        Conf::get('aven', 'key');
    }

    /**
     * test call non exiting config key
     *
     * @return void
     */
    public function testGetMethodNonExistingConfigKey()
    {
        $this->expectException(ConfigException::class);
        Conf::get('app', 'justDummyKey');
    }

    /**
     * test existing config and key
     *
     * @return void
     */
    public function testGetExistingConfig()
    {
        $val = Conf::get('app', 'config_key');
        $this->assertSame('config_value', $val);
    }

    /**
     * test call non exiting config file
     *
     * @return void
     */
    public function testGetMethodAddValueToConfig()
    {
        $val = Conf::get('app', 'added_key', 'added_value');
        $this->assertSame('added_value', $val);
    }

    /**
     * test append to non exiting config file
     *
     * @return void
     */
    public function testAppendMethodNonExistingConfig()
    {
        $this->expectException(ConfigException::class);
        Conf::append('wrongConfig', 'added_key', 'added_value');
    }

    /**
     * test append to non exiting key
     * should add an empty array which we can append to it later
     * fist element will be empty since we are defaulting  value to null
     *
     * @return void
     */
    public function testAppendMethodNonExistingValue()
    {
        Conf::append('app', 'test');
        $this->assertIsArray(Conf::app("test"));
        $this->assertNull(Conf::app("test")[0]);
    }

        /*
     * test append to non exiting key
     * should add an empty arry which we can append to it later
     *
     * @return void
     */
    public function testAppendMethodNonExistingKeyWithValue()
    {
        Conf::append('app', 'test', "hello");
        $this->assertIsArray(Conf::app("test"));
        $this->assertSame('hello', Conf::app("test")[1]);
    }

    /**
     * test append to exiting config  key 
     * should append to the array
     *
     * @return void
     */
    public function testAppendMethodExistingKey()
    {
        Conf::append('app', 'added_key', 'newValue');
        $this->assertSame('newValue', Conf::app('added_key')[1]);
    }

    /**
     * test append to exiting config  key 
     * should append to the array
     *
     * @return void
     */
    public function testAppendMethodAddArrayExistingKey()
    {
        Conf::append('app', 'added_key', array("appended_array"));
        $this->assertContains('appended_array', Conf::app('added_key'));
    }

    public function testConfAllMethodIsValidConfigArray()
    {
        $this->assertIsArray(Conf::all());
        $this->assertArrayHasKey('app', Conf::all());
    }
}