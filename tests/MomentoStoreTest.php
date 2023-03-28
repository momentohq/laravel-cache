<?php

use Illuminate\Support\Facades\Cache;

/**
 * @covers \Momento\Cache\MomentoStore
 */
class MomentoStoreTest extends BaseTest
{
    public function testPutGet_HappyPath()
    {
        $key = uniqid();
        $value = uniqid();
        $putResult = Cache::put($key, $value, 5);
        $this->assertTrue($putResult, "True was expected but received $putResult");
        $getResult = Cache::get($key);
        $this->assertEquals($value, $getResult, "$value was expected but received $getResult");
    }

    public function testIncrement_HappyPath()
    {
        $key = uniqid();
        $putResult = Cache::put($key, 1, 5);
        $this->assertTrue($putResult, "True was expected but received $putResult");
        $incrementResult = Cache::increment($key);
        $this->assertTrue($incrementResult, "True was expected but received $incrementResult");
        $getResult = Cache::get($key);
        $this->assertEquals(2, $getResult, "2 was expected but received $getResult");
    }

    public function testIncrementMiss_HappyPath()
    {
        $key = uniqid();
        $incrementResult = Cache::increment($key);
        $this->assertTrue($incrementResult, "True was expected but received $incrementResult");
        $getResult = Cache::get($key);
        $this->assertEquals(0, $getResult, "0 was expected but received $getResult");
    }

    public function testForget_HappyPath()
    {
        $key = uniqid();
        $value = uniqid();
        $putResult = Cache::put($key, $value, 5);
        $this->assertEquals($value, $putResult, "$value was expected but received $putResult");
        $forgetResult = Cache::forget($key);
        $this->assertTrue($forgetResult, "True was expected but received $forgetResult");
    }
}
