<?php

namespace Tests\Unit;

use OoFile\DotEnv;
use PHPUnit\Framework\TestCase;
//1

class DotEnvTest extends TestCase
{
    /**
     * dot env object.
     *
     * @var DotEnv
     */
    private $env;

    /**
     * set up our object.
     */
    public function setUp() : void
    {
        $this->env = new DotEnv();
    }

    /**
     * test initialize .env.
     *
     * @return void
     */
    public function testInitializeDotEnvFile()
    {
        $path = '';
        $this->env->init([], $path);
        $this->assertFileExists($path.'.env');
    }

    /**
     * assert read from env.
     *
     * @return void
     */
    public function testReadKeyFromEnvFile()
    {
        $v = $this->env->read('APP_NAME');
        $this->assertEquals('Silo', $v);
    }

    /**
     * test read keys from env.
     *
     * @return void
     */
    public function testReadKeyDoesNotExistsFromEnvFile()
    {
        $v = $this->env->read('NO');
        $this->assertNull($v);
        $v = $this->env->read('APP_TEST', 'TRUE');
        $this->assertSame('TRUE', $v);
    }

    /**
     * test delete .env.
     *
     * @return void
     */
    public function testDeleteEnvFile()
    {
        $path = '';
        $del = $this->env->deleteEnvFile($path);
        $this->assertTrue($del);
        $this->assertFileNotExists($path.'.env');
    }
}
