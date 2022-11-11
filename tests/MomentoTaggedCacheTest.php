<?php

use Illuminate\Support\Facades\Cache;
use Momento\Cache\Errors\UnknownError;

/**
 * @covers \Momento\Cache\MomentoTaggedCache
 * @covers \Momento\Cache\MomentoStore
 */
class MomentoTaggedCacheTest extends BaseTest
{
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

    public function testTaggedPutGetMiss_HappyPath()
    {
        $key = uniqid();
        $value = uniqid();
        $tag1 = uniqid();
        $tag2 = uniqid();
        $tag3 = uniqid();
        $putResult = Cache::tags([$tag1, $tag2])->put($key, $value, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $getResult = Cache::tags([$tag3])->get($key);
        $this->assertNull($getResult, "null was expected but received ${getResult}");
    }

    public function testTaggedPut_WithEmptyTags_IsFalse()
    {
        $putResult = Cache::tags([])->put(uniqid(), uniqid(), 5);
        $this->assertFalse($putResult, "False was expected but received ${putResult}");
    }

    public function testTaggedGet_WithEmptyTags_IsNull()
    {
        $key = uniqid();
        $putResult = Cache::tags([uniqid()])->put($key, uniqid(), 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $getResult = Cache::tags([])->get($key);
        $this->assertNull($getResult, "null was expected but received ${getResult}");
    }

    public function testTaggedPutMultipleItems_WithOneTag_HappyPath()
    {
        $key1 = uniqid();
        $value1 = uniqid();
        $key2 = uniqid();
        $value2 = uniqid();
        $tag1 = uniqid();
        $putResult = Cache::tags([$tag1])->put($key1, $value1, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $putResult = Cache::tags([$tag1])->put($key2, $value2, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $getResult = Cache::tags([$tag1])->get($key1);
        $this->assertEquals($value1, $getResult, "${value1} was expected but received ${getResult}");
        $getResult = Cache::tags([$tag1])->get($key2);
        $this->assertEquals($value2, $getResult, "${value2} was expected but received ${getResult}");
    }

    public function testTaggedPut_RegularGet_IsNull()
    {
        $key = uniqid();
        $value = uniqid();
        $tag = uniqid();
        $putResult = Cache::tags([$tag])->put($key, $value, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $getResult = Cache::get($key);
        $this->assertNull($getResult, "null was expected but received ${getResult}");
    }

    public function testTaggedPut_WithWrongOrderTaggedGet_IsNull()
    {
        $key = uniqid();
        $value = uniqid();
        $tag1 = uniqid();
        $tag2 = uniqid();
        $putResult = Cache::tags([$tag1, $tag2])->put($key, $value, 5);
        $this->assertTrue($putResult, "True was expected but received ${putResult}");
        $getResult = Cache::tags([$tag2, $tag1])->get($key);
        $this->assertNull($getResult, "null was expected but received ${getResult}");
    }

    public function testTaggedPutWithTwoTags_TaggedGetWithOnlyOneTag_IsNull()
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

    public function testFlush_ThrowsException()
    {
        $this->expectException(UnknownError::class);
        Cache::tags([uniqid()])->flush();
    }
}
