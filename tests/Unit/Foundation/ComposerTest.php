<?php
declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation;

use Composer\Config;
use FondBot\Foundation\Assets;
use RuntimeException;
use Composer\Script\Event;
use FondBot\Foundation\Composer as TestComposer;
use FondBot\Tests\TestCase;
use Composer\Composer;

class ComposerTest extends TestCase
{
    protected $bootstrapPath = 'bootstrap/app.php';
    protected $bootstrapFolder = 'bootstrap';

    /**
     * @var Assets
     */
    protected $assets;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var Config
     */
    protected $config;
    public function setUp() : void
    {
        parent::setUp();
        $this->event    = $this->mock(Event::class);
        $this->composer = $this->mock(Composer::class);
        $this->assets   = $this->mock(Assets::class);
        $this->config   = $this->mock(Config::class);

        $this->container->share(Assets::class, $this->assets);
    }

    public function tearDown() : void
    {
        parent::tearDown();
        if (file_exists($this->bootstrapPath)) {
            unlink($this->bootstrapPath);
            rmdir($this->bootstrapFolder);
        }
    }

    public function testPostInstallFileNotExist(): void
    {
        $composer = $this->mock(Composer::class);
        $event    = $this->mock(Event::class);
        $config   = $this->mock(Config::class);

        $config->shouldReceive('get')->once()->with('vendor-dir')->andReturn('vendor');
        $composer->shouldReceive('getConfig')->once()->andReturn($config);
        $event->shouldReceive('getComposer')->once()->andReturn($composer);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Bootstrap file does not exist.');
         TestComposer::postInstall($event);
    }

    public function testPostInstall() : void
    {
        $this->createBootstrap();
        $this->config->shouldReceive('get')->once()->with('vendor-dir')->andReturn('vendor');
        $this->composer->shouldReceive('getConfig')->once()->andReturn($this->config);
        $this->event->shouldReceive('getComposer')->once()->andReturn($this->composer);

        $this->assets->shouldReceive('discover')->once();
        TestComposer::postInstall($this->event);
    }

    public function testPostUpdate() : void
    {
        $this->createBootstrap();
        $this->composer->shouldReceive('getConfig')->once()->andReturn($this->config);
        $this->config->shouldReceive('get')->once()->with('vendor-dir')->andReturn('vendor');
        $this->event->shouldReceive('getComposer')->once()->andReturn($this->composer);
        $this->assets->shouldReceive('discover')->once();
        TestComposer::postUpdate($this->event);
    }

    /**
     * Create file and folder bootstrap
     */
    private function createBootstrap() : void
    {
        if (!file_exists($this->bootstrapPath)) {
            mkdir($this->bootstrapFolder);
            file_put_contents($this->bootstrapPath, '');
        }
    }
}
