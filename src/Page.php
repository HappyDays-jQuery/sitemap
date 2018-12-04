<?php

namespace SiteMap;

class Page
{
    /**
     * @var string
     */
    private $scheme;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $pass;
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $tittle;
    /**
     * @var string
     */
    private $breadCram;

    public function __construct($url, $tittle = '', $breadCram = '')
    {
        $arrRet = parse_url($url);
        $this->setScheme($arrRet['scheme']);
        $this->setUser($arrRet['user']);
        $this->setPass($arrRet['pass']);
        $this->setHost($arrRet['host']);
        $this->setUrl($url);
        $this->setPath($arrRet['path']);
        $this->setTittle($tittle);
        $this->setBreadCram($breadCram);
    }

    public function __toString()
    {
        return "{$this->getHost()}\t{$this->getUrl()}\t{$this->getPath()}\t{$this->getTittle()}\t{$this->getBreadCram()}\n";
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getTittle()
    {
        return $this->tittle;
    }

    /**
     * @param string $tittle
     */
    public function setTittle($tittle)
    {
        $this->tittle = $tittle;
    }

    /**
     * @return string
     */
    public function getBreadCram()
    {
        return $this->breadCram;
    }

    /**
     * @param string $breadCram
     */
    public function setBreadCram($breadCram)
    {
        $this->breadCram = $breadCram;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }
}
