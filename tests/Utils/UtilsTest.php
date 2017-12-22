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

    public function testNormalize()
    {
        $this->assertEquals("aaa/bbb", Utils::normalize('aaa/bbb'));
        $this->assertEquals("aaa/bbb", Utils::normalize('aaa//bbb'));
        $this->assertEquals("aaa/bbb", Utils::normalize('aaa///bbb'));
        $this->assertEquals("/aaa/bbb", Utils::normalize('/aaa///bbb'));
        $this->assertEquals("/aaa/bbb", Utils::normalize('//aaa///bbb'));
        $this->assertEquals("/aaa/bbb", Utils::normalize('///aaa///bbb'));
    }

    public function testNormalizeRelativePath()
    {
        $this->assertEquals("bbb", Utils::normalize('aaa/../bbb'));
        $this->assertEquals("../bbb", Utils::normalize('aaa/../../bbb'));
        $this->assertEquals("../../bbb", Utils::normalize('aaa/../../../bbb'));
        $this->assertEquals("../../bbb", Utils::normalize('aaa//..//..//..//bbb'));
        $this->assertEquals("../../ccc", Utils::normalize('aaa/../../../bbb/../ccc'));
    }

    public function testNormalizeCurrentPath()
    {
        $this->assertEquals("aaa/bbb", Utils::normalize('aaa/./bbb'));
        $this->assertEquals("bbb", Utils::normalize('./bbb'));
        $this->assertEquals("aaa/bbb/ccc", Utils::normalize('aaa/./bbb/./ccc'));
    }

    public function testNormalizeAbsolutePath()
    {
        $this->assertEquals("/aaa/bbb", Utils::normalize('/aaa/./bbb'));
        $this->assertEquals("/bbb", Utils::normalize('/aaa/../bbb'));
        $this->assertEquals("/bbb", Utils::normalize('/aaa/../../bbb'));
        $this->assertEquals("/bbb", Utils::normalize('/aaa/../../../bbb'));
    }
}
