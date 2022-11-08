<?php

use Illuminate\Support\Facades\Cache;

class MomentoTaggedCacheTests extends BaseTest
{
    /**
     * @covers \Momento\Cache\MomentoTaggedCache
     */
    public function testTaggedPutGet_HappyPath()
    {
        $key = uniqid();
        $value = uniqid();
        $tag1 = uniqid();
        $tag2 = uniqid();
        $putResult = Cache::tags([$tag1, $tag2])->put($key, $value, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $getResult = Cache::tags([$tag1, $tag2])->get($key);
        $this->assertEquals($value, $getResult, "${value} was expected but received ${getResult}");
    }

    /**
     * @covers \Momento\Cache\MomentoTaggedCache
     */
    public function testTaggedPutGetMiss_HappyPath()
    {
        $key = uniqid();
        $value = uniqid();
        $tag1 = uniqid();
        $tag2 = uniqid();
        $putResult = Cache::tags([$tag1, $tag2])->put($key, $value, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $getResult = Cache::tags([$tag1])->get($key);
        $this->assertNull($getResult, "null was expected but received ${getResult}");
    }
}
