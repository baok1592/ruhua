<?php

use League\Flysystem\Cached\Storage\Stash;
use PHPUnit\Framework\TestCase;

class StashTests extends TestCase
{
    public function testLoadFail()
    {
        $pool = Mockery::mock('Stash\Pool');
        $item = Mockery::mock('Stash\Item');
        $item->shouldReceive('get')->once()->andReturn(null);
        $item->shouldReceive('isMiss')->once()->andReturn(true);
        $pool->shouldReceive('getItem')->once()->andReturn($item);
        $cache = new Stash($pool);
        $cache->load();
        $this->assertFalse($cache->isComplete('', false));
    }

    public function testLoadSuccess()
    {
        $response = json_encode([[], ['' => true]]);
        $pool = Mockery::mock('Stash\Pool');
        $item = Mockery::mock('Stash\Item');
        $item->shouldReceive('get')->once()->andReturn($response);
        $item->shouldReceive('isMiss')->once()->andReturn(false);
        $pool->shouldReceive('getItem')->once()->andReturn($item);
        $cache = new Stash($pool);
        $cache->load();
        $this->assertTrue($cache->isComplete('', false));
    }

    public function testSave()
    {
        $response = json_encode([[], []]);
        $pool = Mockery::mock('Stash\Pool');
        $item = Mockery::mock('Stash\Item');
        $item->shouldReceive('set')->once()->andReturn($response);
        $pool->shouldReceive('getItem')->once()->andReturn($item);
        $cache = new Stash($pool);
        $cache->save();
    }
}
