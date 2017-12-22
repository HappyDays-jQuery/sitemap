<?php

namespace SiteMap;

use Goutte\Client;

class Finder
{
    /**
     * @var Page
     */
    private $page;
    /**
     * @var array
     */
    private $links;

    /**
     * Finder constructor.
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->links = [];
        $this->page = $page;
        Utils::display("target: {$page->getUrl()}");
        $this->crawl();
    }

    public function crawl()
    {
        $cli = new Client();
        $crawler = $cli->request('GET', $this->page->getUrl());

        try {
            $this->page->setTittle($crawler->filter('title')->text());
            $this->page->setBreadCram($crawler->filter('h1')->text());
            Utils::display("タイトル: " . $this->page->getTittle());
            Utils::display("見出し: " . $this->page->getBreadCram());
        } catch (\Exception $e) {

        }
        $crawler->filter('a')->each(function ($element) {
            $tmpUrl = $this->getUrl($element->attr('href'));
            if ($tmpUrl != "" && !in_array($tmpUrl, $this->links)) {
                $this->links[] = $tmpUrl;
            }
        });
    }

    public function getUrl($path)
    {
        if (Utils::isTel($path)
            || Utils::isMailTo($path)
            || Utils::isJavaScript($path)
            || Utils::isAnchor($path)
        ) {
            return '';
        }

        if (Utils::isNoScheme($path)) {
            $path = $this->page->getScheme() . ":" . $path;
        }

        if (Utils::isAbsolute($path)) {
            $path = $this->page->getScheme() . "://" . $this->page->getHost() . $path;
        }

        if (!Utils::isUrl($path)) {
            $path = $this->page->getScheme() . "://" . $this->page->getHost() . "/" . Utils::normalize($path);
        }

        return $path;
    }

    public function getLinks()
    {
        return $this->links;
    }
}
