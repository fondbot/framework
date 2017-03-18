<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Services;

use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Database\Services\ChannelService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @property Channel[] items
 * @property Channel channel
 * @property ChannelService service
 */
class ChannelServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new ChannelService(
            $this->channel = resolve(Channel::class)
        );

        /** @var Channel $enabled */
        $this->items = [
            'enabled' => $this->service->create([
                'name' => $this->faker()->name,
                'driver' => $this->faker()->word,
                'parameters' => '',
                'is_enabled' => true,
            ]),
            'disabled' => $this->service->createOrUpdate([
                'name' => $this->faker()->name,
                'driver' => $this->faker()->word,
                'parameters' => '',
                'is_enabled' => false,
            ], []),
        ];
    }

    public function test_findEnabled()
    {
        $result = $this->service->findEnabled();

        $this->assertCount(1, $result);
        $this->assertSame($this->items['enabled']->name, $result->first()->name);
        $this->assertNotSame($this->items['disabled']->name, $result->first()->name);
    }

    public function test_findDisabled()
    {
        $result = $this->service->findDisabled();

        $this->assertCount(1, $result);
        $this->assertSame($this->items['disabled']->id, $result->first()->id);
        $this->assertNotSame($this->items['enabled']->id, $result->first()->id);
    }

    public function test_findByName()
    {
        /** @var Channel $item */
        $item = collect($this->items)->random();
        $result = $this->service->findByName($item->name);

        $this->assertSame($item->id, $result->id);
    }

    public function test_enable()
    {
        $this->service->enable($this->items['disabled']);

        $item = $this->items['disabled']->fresh();

        $this->assertTrue($item->is_enabled);
    }

    public function test_disable()
    {
        $this->service->disable($this->items['enabled']);

        $item = $this->items['enabled']->fresh();

        $this->assertFalse($item->is_enabled);
    }

    public function test_all()
    {
        $result = $this->service->all();

        $this->assertCount(count($this->items), $result);
    }

    public function test_findById()
    {
        /** @var Channel $item */
        $item = collect($this->items)->random();

        $result = $this->service->findById($item->id);

        $this->assertEquals($item->id, $result->id);
    }

    public function test_delete()
    {
        /** @var Channel $item */
        $item = collect($this->items)->random();

        $this->service->delete($item);

        $this->assertNull($item->fresh());
    }
}
