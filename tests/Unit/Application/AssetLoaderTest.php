<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use FondBot\Application\Assets;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

class AssetLoaderTest extends TestCase
{
    /** @var Filesystem */
    private $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container->add('bootstrap_path', 'bootstrap');

        $adapter = new MemoryAdapter;
        $filesystem = new Filesystem($adapter);

        $asset1 = json_encode([
            'extra' => [
                'fondbot' => [
                    'driver' => 'Acme\FooDriver',
                ],
            ],
        ]);

        $asset2 = json_encode([
            'extra' => [
                'fondbot' => [
                    'driver' => 'Acme\BarDriver',
                ],
            ],
        ]);

        $filesystem->write('vendor/foo/composer.json', $asset1);
        $filesystem->write('vendor/bar/composer.json', $asset2);
        $filesystem->write('vendor/baz/composer.json', json_encode([]));

        $this->filesystem = $filesystem;
    }

    public function test_all(): void
    {
        $this->filesystem->put('bootstrap/assets.json', json_encode([
            'driver' => ['Acme\BarDriver', 'Acme\FooDriver'],
        ]));

        $loader = new Assets($this->filesystem);
        $result = $loader->all();

        $expected = [
            'driver' => [
                'Acme\BarDriver',
                'Acme\FooDriver',
            ],
        ];

        $this->assertSame($expected, $result);
    }

    public function test_all_by_type(): void
    {
        $this->filesystem->put('bootstrap/assets.json', json_encode([
            'driver' => ['Acme\BarDriver', 'Acme\FooDriver'],
        ]));

        $loader = new Assets($this->filesystem);
        $result = $loader->all('driver');

        $expected = [
            'Acme\BarDriver',
            'Acme\FooDriver',
        ];

        $this->assertSame($expected, $result);
    }

    public function test_discover(): void
    {
        $loader = new Assets($this->filesystem);
        $loader->discover();

        $this->assertTrue($this->filesystem->has('bootstrap/assets.json'));

        $contents = $this->filesystem->get('bootstrap/assets.json')->read();
        $assets = json_decode($contents, true);

        $expected = [
            'driver' => [
                'Acme\BarDriver',
                'Acme\FooDriver',
            ],
        ];

        $this->assertSame($expected, $assets);

        // Recompile assets
        $loader->discover();

        $this->assertSame($expected, $assets);
    }
}
