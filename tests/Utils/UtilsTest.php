<?php

namespace SiteMap;

class FileIoTest extends CommonTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testMergeAndRemove()
    {
        $base = new URI('http://dmain/aaa/bbb/ccc');

        $this->assertEquals("/aaa/bbb/ddd", Utils::removeDotSegments($base->merge($base, './ddd')));
        $this->assertEquals("/aaa/ddd", Utils::removeDotSegments($base->merge($base, '../ddd')));
        $this->assertEquals("/ddd", Utils::removeDotSegments($base->merge($base, '../../ddd')));
        $this->assertEquals("/ddd", Utils::removeDotSegments($base->merge($base, '../../../ddd')));

        //絶対パスは対象外
        $this->assertEquals("/aaa/bbb//ddd", Utils::removeDotSegments($base->merge($base, '/ddd')));

    }
}
