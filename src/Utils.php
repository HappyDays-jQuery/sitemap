<?php

namespace SiteMap;

class Utils
{
    public static function display($message = "")
    {
        echo $message . "\n";
        Utils::flushBuffers();
    }

    public static function flushBuffers()
    {
        ob_end_flush();
        ob_flush();
        flush();
        ob_start();
    }

    public static function isUrl($str)
    {
        //return filter_var($str, FILTER_VALIDATE_URL);
        return preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str);
    }

    public static function isStaticFile($str)
    {
        return preg_match("/\.js|\.css|\.pdf|\.jpg|\.gif|\.png|\.xml|\.rss|\.rdf/", $str);
    }

    public static function isTel($path)
    {
        return stripos($path, 'tel:') === 0;
    }

    public static function isMailTo($path)
    {
        return stripos($path, 'mailto:') === 0;
    }

    public static function isJavaScript($path)
    {
        return stripos($path, 'javascript:') === 0;
    }

    public static function isAbsolute($path)
    {
        return strpos($path, '/') === 0;
    }

    public static function isNoScheme($path)
    {
        return strpos($path, '//') === 0;
    }

    public static function isAnchor($path)
    {
        return strpos($path, '#') === 0;
    }

    public static function normalize($path)
    {
        $parts = [];
        $segments = explode('/', preg_replace('@/++@', '/', $path));
        foreach ($segments as $segment) {
            Utils::relativePath($segment, $parts);
        }
        $parts = implode('/', $parts);

        if (Utils::isAbsolute($path)) {
            $parts = preg_replace('@^(?:\.{2}/)++@', '/', $parts);
        }

        return $parts;
    }

    public static function relativePath($segment, &$parts)
    {
        if ($segment === '.') {
            return;
        }

        if (null === $tail = array_pop($parts)) {
            $parts[] = $segment;
        } elseif ($segment === '..') {
            if ($tail === '..') {
                $parts[] = $tail;
            }
            if ($tail === '..' || $tail === '') {
                $parts[] = $segment;
            }
        }

        if ($tail !== null && $segment !== '..') {
            $parts[] = $tail;
            $parts[] = $segment;
        }
    }

    public static function isSameDomain($domain, $target)
    {
        return $domain == parse_url($target, PHP_URL_HOST);
    }
}
