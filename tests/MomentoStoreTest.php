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

    public function testDecrement_HappyPath()
    {
        $key = uniqid();
        $putResult = Cache::put($key, 10, 5);
        $this->assertTrue($putResult, "True was expected but received $putResult");
        $decrementResult = Cache::decrement($key);
        $this->assertTrue($decrementResult, "True was expected but received $decrementResult");
        $getResult = Cache::get($key);
        $this->assertEquals(9, $getResult, "9 was expected but received $getResult");
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

    public function testPutManyGetMany_HappyPath()
    {
        $keys = [uniqid(), uniqid(), uniqid()];
        $values = [uniqid(), uniqid(), uniqid()];
        $items = array(
            $keys[0] => $values[0],
            $keys[1] => $values[1],
            $keys[2] => $values[2]
        );
        $putManyResult = Cache::putMany($items, 5);
        $this->assertTrue($putManyResult, "True was expected but received $putManyResult");
        $getManyResult = Cache::many($keys);
        $this->assertEquals($values, $getManyResult);
    }

    public function testFlush_HappyPath()
    {
        $key = uniqid();
        $value = uniqid();
        $putResult = Cache::put($key, $value, 5);
        $this->assertTrue($putResult, "True was expected but received $putResult");
        $flushResult = Cache::flush();
        $this->assertTrue($flushResult, "True was expected but received $flushResult");
        $getResult = Cache::get($key);
        $this->assertNull($getResult, "Null was expected but received $getResult");
    }
}
