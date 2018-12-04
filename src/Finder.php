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
    }

    /**
     * @return Page
     */
    public function crawl()
    {
        $cli = new Client();
        $cli->followRedirects(false);
        $cli->setMaxRedirects(-1);
        if ($this->page->getUser()) {
            $cli->setAuth($this->page->getUser(), $this->page->getPass());
        }
        $crawler = $cli->request('GET', $this->page->getUrl());

        try {
            $this->page->setTittle($crawler->filter('title')->text());
            $this->page->setBreadCram($crawler->filter('h1')->text());
            Utils::display("タイトル: " . $this->page->getTittle());
            Utils::display("見出し: " . $this->page->getBreadCram());
        } catch (\Exception $e) {
        }
        $crawler->filter('a')->each(function ($element) {
            $tmpUrl = $this->getUrl(preg_replace('/\n|\r|\r\n/', '', ltrim($element->attr('href'))));
            if ($tmpUrl != "" && !in_array($tmpUrl, $this->links)) {
                $this->links[] = $tmpUrl;
            }
        });

        return $this->page;
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
        if (Utils::isNoScheme($path) && $path != '//') {
            $path = $this->page->getScheme() . ":" . $path;
        }

        if (!Utils::isUrl($path)) {
            $target = new URI($path);
            $path = $target->resolve(new URI($this->page->getUrl()));
        }

        return $path;
    }

    public function getLinks()
    {
        return $this->links;
    }
}
