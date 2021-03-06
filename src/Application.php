<?php

namespace SiteMap;

class Application
{
    /**
     * @var int
     */
    private $sleep;
    /**
     * @var array
     */
    private $unvisited;
    /**
     * @var array
     */
    private $visited;
    /**
     * @var array
     */
    private $staticFiles;
    /**
     * @var array
     */
    private $externals;
    /**
     * @var string
     */
    private $domain;
    /**
     * @var Page[]
     */
    private $visitedPages;

    /**
     * Application constructor.
     * @param string $url
     * @param int    $sleep
     */
    public function __construct($url, $sleep = 2)
    {
        $this->domain = parse_url($url, PHP_URL_HOST);
        $this->sleep = $sleep;
        $this->unvisited = [$url];
        $this->visited = [];
        $this->staticFiles = [];
        $this->externals = [];
    }

    public function run()
    {
        while ($this->isRemains()) {
            sleep($this->sleep);
            $target = array_shift($this->unvisited);
            $finder = new Finder(new Page($target));
            $page = $finder->crawl();
            $this->visited[] = $target;
            $this->visitedPages[hash('sha256', $target)] = $page;
            foreach ($finder->getLinks() as $url) {
                $this->assortment($url);
            }
            unset($finder);
        }

        $this->report();
    }

    /**
     * @param string $url
     */
    public function assortment($url)
    {
        if (Utils::isJavaScript($url)
            || Utils::isAnchor($url)
            || in_array($url, $this->visited)
            || in_array($url, $this->unvisited)
        ) {
            return;
        }

        if (!Utils::isSameDomain($this->domain, $url)) {
            if (!in_array($url, $this->externals)) {
                $this->externals[] = $url;
            }
            return;
        }

        if (Utils::isStaticFile($url)) {
            if (!in_array($url, $this->staticFiles)) {
                $this->staticFiles[] = $url;
            }
            return;
        }

        $this->unvisited[] = $url;
    }

    public function isRemains()
    {
        return count($this->unvisited) > 0;
    }

    public function report()
    {
        sort($this->visited);
        sort($this->staticFiles);
        sort($this->externals);

        echo "scan end.\n\n";
        echo "visited (" . count($this->visited) . ") : \n";
        foreach ($this->visited as $url) {
            echo (string)$this->visitedPages[hash('sha256', $url)] . "\n";
        }
        echo "static files (" . count($this->staticFiles) . ") : \n" . implode($this->staticFiles, "\n") . "\n";
        echo "external (" . count($this->externals) . ") : \n" . implode($this->externals, "\n") . "\n";
    }
}
