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
     * Application constructor.
     * @param string $url
     * @param int $sleep
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
            $this->visited[] = $target;
            $this->assortment($finder->getLinks());
            unset($finder);
        }

        sort($this->visited);
        sort($this->staticFiles);
        sort($this->externals);

        echo "scan end.\n\n";
        echo "visited (" . count($this->visited) . ") : \n" . implode($this->visited, "\n") . "\n";
        echo "static files (" . count($this->staticFiles) . ") : \n" . implode($this->staticFiles, "\n") . "\n";
        echo "external (" . count($this->externals) . ") : \n" . implode($this->externals, "\n" ) . "\n";
    }

    /**
     * @param array $urls
     */
    public function assortment($urls)
    {
        foreach ($urls as $url) {
            if (Utils::isJavaScript($url)) {
                continue;
            }

            if (Utils::isAnchor($url)) {
                continue;
            }

            if (!Utils::isSameDomain($this->domain, $url)) {
                if (!in_array($url, $this->externals)) {
                    $this->externals[] = $url;
                }
                continue;
            }

            if (Utils::isStaticFile($url)) {
                if (!in_array($url, $this->staticFiles)) {
                    $this->staticFiles[] = $url;
                }
                continue;
            }

            if (in_array($url, $this->visited)) {
                continue;
            }

            if (in_array($url, $this->unvisited)) {
                continue;
            }

            $this->unvisited[] = $url;
        }
    }

    public function isRemains()
    {
        return count($this->unvisited) > 0;
    }
}
