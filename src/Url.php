<?php

namespace SiteMap;

class Url
{
    /**
     * @var []
     */
    private $parse = array(
        "scheme" => null,
        "user" => null,
        "pass" => null,
        "host" => null,
        "port" => null,
        "query" => null,
        "fragment" => null
    );

    /**
     * @var string
     */
    private $base;

    public function getParse($key)
    {
        return isset($this->parse[$key]) ? $this->parse[$key] : $this->parse;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setParse($key, $value)
    {
        $this->parse[$key] = $value;
    }

    /**
     * Url constructor.
     * @param string $base
     */
    public function __construct($base)
    {
        $this->base = $base;
        $this->parse = parse_url($base);
    }

    /**
     * resolve
     * 相対パスから絶対URLを返す
     *
     * @param string $relationalPath 相対パス
     * @return string
     */
    public function resolve($relationalPath)
    {
        if ($relationalPath == '') {
            return $this->base;
        }
        $relationalPath = Utils::normalize($relationalPath);
        if (@strpos($this->getParse("path"), "/", (@strlen($this->getParse("path")) - 1)) !== false) {
            $this->setParse("path", $this->getParse("path") . "./");
        }
        if (preg_match("#^/.*$#", $relationalPath)) {
            return $this->getParse("scheme") . "://" . $this->getParse("host") . $relationalPath;
        }
        if (preg_match("#^https?://#", $relationalPath)) {
            return $relationalPath;
        }

        $basePath = explode("/", dirname($this->getParse("path")));
        $relPath = explode("/", $relationalPath);

        foreach ($relPath as $relDirName) {
            if ($relDirName == ".") {
                array_shift($basePath);
                array_unshift($basePath, "");
                continue;
            }
            if ($relDirName == "..") {
                array_pop($basePath);
                if (count($basePath) == 0) {
                    $basePath = array("");
                }
                continue;
            }
            array_push($basePath, $relDirName);
        }
        $path = Utils::normalize(implode("/", $basePath));
        return $this->getParse("scheme") . "://" . $this->getParse("host") . $path;
    }
}
