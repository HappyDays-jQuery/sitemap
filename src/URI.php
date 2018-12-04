<?php

namespace SiteMap;

class URI
{
    /**
     * @var string
     */
    private $scheme = "";
    /**
     * @var string
     */
    private $authority = "";
    /**
     * @var string
     */
    private $path = "";
    /**
     * @var string
     */
    private $query = "";
    /**
     * @var string
     */
    private $fragment = "";

    /**
     * URI constructor.
     * @param string $str
     */
    public function __construct($str)
    {
        if (!$str) {
            $str = "";
        }
        preg_match('/^(?:([^:\/?\#]+):)?(?:\/\/([^\/?\#]*))?([^?\#]*)(?:\?([^\#]*))?(?:\#(.*))?/', $str, $matches);
        /*
         * array(6) {
         *   [0] =>  string(43) "http://id:pass@host/path/to/file?query#hash"
         *   [1] =>  string(4) "http"
         *   [2] =>  string(12) "id:pass@host"
         *   [3] =>  string(13) "/path/to/file"
         *   [4] =>  string(5) "query"
         *   [5] =>  string(4) "hash"
         * }
         */
        $this->setScheme(isset($matches[1]) ? $matches[1] : "")
            ->setAuthority(isset($matches[2]) ? $matches[2] : "")
            ->setPath(isset($matches[3]) ? $matches[3] : "")
            ->setQuery(isset($matches[4]) ? $matches[4] : "")
            ->setFragment(isset($matches[5]) ? $matches[5] : "");
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $str = "";
        if ($this->getScheme()) {
            $str .= $this->getScheme() . ":";
        }
        if ($this->getAuthority()) {
            $str .= "//" . $this->getAuthority();
        }
        if ($this->getPath()) {
            $str .= $this->getPath();
        }
        if ($this->getQuery()) {
            $str .= "?" . $this->getQuery();
        }
        if ($this->getFragment()) {
            $str .= "#" . $this->getFragment();
        }

        return $str;
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
     * @return URI
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * @param string $authority
     * @return URI
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;

        return $this;
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
     * @return URI
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     * @return URI
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param string $fragment
     * @return URI
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * @param URI    $base
     * @param string $relPath
     * @return string
     */
    public function merge($base, $relPath)
    {
        if ($base->getAuthority() && !$base->getPath()) {
            return "/" . $relPath;
        }

        preg_match("/^(.*)\//", $base->getPath(), $matches);

        return $matches[0] . $relPath;
    }

    /**
     * @param URI $base
     * @return URI
     */
    public function resolve($base)
    {
        $target = new URI("");

        return $target
            ->setScheme($base->getScheme())
            ->setAuthority($base->getAuthority())
            ->setPath(
                Utils::isAbsolute($this->getPath()) ?
                    Utils::removeDotSegments($this->getPath())
                    : Utils::removeDotSegments($this->merge($base, $this->getPath()))
            )
            ->setQuery($this->getQuery())
            ->setFragment($this->getFragment());
    }
}
