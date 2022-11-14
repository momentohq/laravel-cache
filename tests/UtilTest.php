<?php

use Momento\Cache\MomentoTaggedCache;

/**
 * @covers \Momento\Cache\MomentoTaggedCache\
 */
class UtilTest extends BaseTest
{
    public function testCreateNewKey()
    {
        $tags = ['foo', 'bar'];
        $key = ['baz'];
        $expectedNewKey = 'foo-bar-baz';
        $newKey = MomentoTaggedCache::createNewKey($tags, $key);
        $this->assertEquals($expectedNewKey, $newKey, "${expectedNewKey} was expected but received ${newKey}.");
    }

    public function testValidateTags()
    {
        $result = MomentoTaggedCache::validateTags([]);
        $this->assertFalse($result, "False was expected but received ${result}");
        $result = MomentoTaggedCache::validateTags([uniqid(), uniqid()]);
        $this->assertTrue($result, "True was expected but received ${result}");
    }

}