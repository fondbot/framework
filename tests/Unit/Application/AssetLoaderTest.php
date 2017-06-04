<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use FondBot\Application\Assets;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;

class AssetLoaderTest extends TestCase
{
    private $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_discover(): void
    {
        $loader = new Assets($this->filesystem);
        $result = $loader->discover();

        $expected = [
            'driver' => [
                'Acme\BarDriver',
                'Acme\FooDriver',
            ],
        ];

        $this->assertSame($expected, $result);
    }

    public function test_discover_by_type(): void
    {
        $loader = new Assets($this->filesystem);
        $result = $loader->discover('driver');

        $expected = [
            'Acme\BarDriver',
            'Acme\FooDriver',
        ];

        $this->assertSame($expected, $result);
    }
}
