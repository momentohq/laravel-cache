<?php

use Illuminate\Support\Facades\Cache;

class MomentoStoreTests extends BaseTest
{

    /**
     * @covers \Momento\Cache\MomentoStore
     */
    public function testPutGet_HappyPath()
    {
        $key = uniqid();
        $value = uniqid();
        $putResult = Cache::put($key, $value, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $getResult = Cache::get($key);
        $this->assertEquals($value, $getResult, "${value} was expected but received ${getResult}");
    }


    /**
     * @covers \Momento\Cache\MomentoStore
     */
    public function testIncrement_HappyPath()
    {
        $key = uniqid();
        $putResult = Cache::put($key, 1, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $incrementResult = Cache::increment($key);
        $this->assertTrue($incrementResult, "True was expected but received ${incrementResult}");
        $getResult = Cache::get($key);
        $this->assertEquals(2, $getResult, "2 was expected but received ${getResult}");
    }

    /**
     * @covers \Momento\Cache\MomentoStore
     */
    public function testIncrementMiss_HappyPath()
    {
        $key = uniqid();
        $incrementResult = Cache::increment($key);
        $this->assertTrue($incrementResult, "True was expected but received ${incrementResult}");
        $getResult = Cache::get($key);
        $this->assertEquals(0, $getResult, "0 was expected but received ${getResult}");
    }

    /**
     * @covers \Momento\Cache\MomentoStore
     */
    public function testForget_HappyPath()
    {
        $key = uniqid();
        $value = uniqid();
        $putResult = Cache::put($key, $value, 5);
        $this->assertEquals($value, $putResult, "${value} was expected but received ${putResult}");
        $forgetResult = Cache::forget($key);
        $this->assertTrue($forgetResult, "True was expected but received ${forgetResult}");
    }

    /**
     * @covers \Momento\Cache\MomentoStore
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
}
