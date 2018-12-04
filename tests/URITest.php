<?php

namespace SiteMap;

class URITest extends CommonTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testConstruct()
    {
        $uri = new URI('http://id:pass@host.name/path/to/file?query#hash');
        $this->assertInstanceOf("SiteMap\URI", $uri);
    }

    public function testResolve()
    {
        $base = new URI('http://id:pass@host.name/path/to/file?query#hash');

        $target = new URI('/aaa');
        $this->assertEquals("http://id:pass@host.name/aaa", (string)$target->resolve($base));

        $target = new URI('../aaa');
        $this->assertEquals("http://id:pass@host.name/path/aaa", (string)$target->resolve($base));

        $target = new URI('../../aaa');
        $this->assertEquals("http://id:pass@host.name/aaa", (string)$target->resolve($base));

        $target = new URI('../../../aaa');
        $this->assertEquals("http://id:pass@host.name/aaa", (string)$target->resolve($base));

        $target = new URI('./aaa');
        $this->assertEquals("http://id:pass@host.name/path/to/aaa", (string)$target->resolve($base));

        $target = new URI('bbb/aaa');
        $this->assertEquals("http://id:pass@host.name/path/to/bbb/aaa", (string)$target->resolve($base));

        $target = new URI('bbb/../aaa');
        $this->assertEquals("http://id:pass@host.name/path/to/aaa", (string)$target->resolve($base));

        $target = new URI('bbb/.././aaa');
        $this->assertEquals("http://id:pass@host.name/path/to/aaa", (string)$target->resolve($base));

        $target = new URI('bbb/.././../aaa');
        $this->assertEquals("http://id:pass@host.name/path/aaa", (string)$target->resolve($base));

        $target = new URI('bbb/../ccc/../aaa');
        $this->assertEquals("http://id:pass@host.name/path/to/aaa", (string)$target->resolve($base));

        $target = new URI('http://hogehost/aaa');
        $this->assertEquals("http://id:pass@host.name/aaa", (string)$target->resolve($base));
    }

    public function testToString()
    {
        $uri = new URI('http://host.name/');
        $this->assertEquals("http://host.name/", (string)$uri);

        $uri = new URI('/path/to/file');
        $this->assertEquals("/path/to/file", (string)$uri);

        $uri = new URI('../path/to/file');
        $this->assertEquals("../path/to/file", (string)$uri);

        $uri = new URI('../path/to/./file');
        $this->assertEquals("../path/to/./file", (string)$uri);

        $uri = new URI('?query');
        $this->assertEquals("?query", (string)$uri);

        $uri = new URI('#feagment');
        $this->assertEquals("#feagment", (string)$uri);

        $uri = new URI('http://id:pass@host.name/path/to/file?query#hash');
        $this->assertEquals("http://id:pass@host.name/path/to/file?query#hash", (string)$uri);
    }

    public function testRemoveDotSegment()
    {
        $this->assertEquals("", (string)Utils::removeDotSegments(""));
        $this->assertEquals("/", (string)Utils::removeDotSegments("/"));
        $this->assertEquals("//", (string)Utils::removeDotSegments("//"));
        $this->assertEquals("./", (string)Utils::removeDotSegments("./"));
        $this->assertEquals("../", (string)Utils::removeDotSegments("../"));
        $this->assertEquals("../", (string)Utils::removeDotSegments("../../"));
        $this->assertEquals("../", (string)Utils::removeDotSegments(".././../"));
        $this->assertEquals("../", (string)Utils::removeDotSegments("../././../"));
        $this->assertEquals("../", (string)Utils::removeDotSegments(".././.././../"));

        $this->assertEquals("/ddd", (string)Utils::removeDotSegments("/ddd"));
        $this->assertEquals("ddd", (string)Utils::removeDotSegments("ddd"));
        $this->assertEquals("../ddd", (string)Utils::removeDotSegments("../ddd"));
        $this->assertEquals("../ddd", (string)Utils::removeDotSegments("../../ddd"));
        $this->assertEquals("../eee/ddd", (string)Utils::removeDotSegments("../../eee/ddd"));
        $this->assertEquals("../ddd", (string)Utils::removeDotSegments("../../eee/../ddd"));
        $this->assertEquals("../fff/ddd", (string)Utils::removeDotSegments("../../eee/../fff/ddd"));
        $this->assertEquals("../fff/ddd", (string)Utils::removeDotSegments("..//../fff/ddd"));
    }

    public function testMerge()
    {
        $base = new URI('http://dmain/aaa/bbb/ccc');

        $this->assertEquals("/aaa/bbb/ddd", $base->merge($base, 'ddd'));
        $this->assertEquals("/aaa/bbb/ddd/", $base->merge($base, 'ddd/'));
        $this->assertEquals("/aaa/bbb//ddd", $base->merge($base, '/ddd'));

        $this->assertEquals("/aaa/bbb/./ddd", $base->merge($base, './ddd'));
        $this->assertEquals("/aaa/bbb/../ddd", $base->merge($base, '../ddd'));
        $this->assertEquals("/aaa/bbb/../../ddd", $base->merge($base, '../../ddd'));

        $base = new URI('http://dmain');
        $this->assertEquals("/ddd", $base->merge($base, 'ddd'));
        $this->assertEquals("/ddd/", $base->merge($base, 'ddd/'));
        $this->assertEquals("//ddd", $base->merge($base, '/ddd'));

        $this->assertEquals("/./ddd", $base->merge($base, './ddd'));
        $this->assertEquals("/../ddd", $base->merge($base, '../ddd'));
        $this->assertEquals("/../../ddd", $base->merge($base, '../../ddd'));
    }
}
