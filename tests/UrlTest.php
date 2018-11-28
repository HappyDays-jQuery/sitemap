<?php

namespace SiteMap;

class UrlTest extends CommonTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testResolve()
    {
        $url = new Url('http://host/');
        $this->assertEquals("http://host/", $url->resolve(''));
        $this->assertEquals("http://host/", $url->resolve('/'));
        $this->assertEquals("http://host/ddd", $url->resolve('ddd'));
        $this->assertEquals("http://host/ddd", $url->resolve('../ddd'));
        $this->assertEquals("http://host/", $url->resolve('//'));

    	$url = new Url('http://host/aaa/bbb/cc.html');
        $this->assertEquals("http://host/aaa/bbb/cc.html", $url->resolve(''));
        $this->assertEquals("http://host/", $url->resolve('/'));
		$this->assertEquals("http://host/ddd", $url->resolve('/ddd'));
		$this->assertEquals("http://host/aaa/bbb/ddd", $url->resolve('ddd'));
		$this->assertEquals("http://host/aaa/ddd", $url->resolve('../ddd'));
		$this->assertEquals("http://host/ddd", $url->resolve('../../ddd'));
		$this->assertEquals("http://host/eee/ddd", $url->resolve('../../eee/ddd'));
		$this->assertEquals("http://host/ddd", $url->resolve('../../eee/../ddd'));
		$this->assertEquals("http://host/fff/ddd", $url->resolve('../../eee/../fff/ddd'));
		$this->assertEquals("http://host/fff/ddd", $url->resolve('..//../fff/ddd'));
        $this->assertEquals("http://host/ddd", $url->resolve('../../../ddd'));
    }
}
