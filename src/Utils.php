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
        return preg_match("/\.js$|\.css$|\.pdf$|\.jpg$|\.gif$|\.png$|\.xml$|\.rss$|\.rdf$|\.mov$/", strtolower($str));
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

    public static function isSameDomain($domain, $target)
    {
        return $domain == parse_url($target, PHP_URL_HOST);
    }


    public static function removeDotSegments($path)
    {
        if (!$path) {
            return "";
        }

        $newPath = preg_replace("/\/\.\//", '/', $path);
        $newPath = preg_replace("/\/\.$/", '/', $newPath);

        while (preg_match("/\/((?!\.\.\/)[^\/]*)\/\.\.\//", $newPath)) {
            $newPath = preg_replace("/\/((?!\.\.\/)[^\/]*)\/\.\.\//", '/', $newPath);
        }
        $newPath = preg_replace("/\/([^\/]*)\/\.\.$/", '/', $newPath);

        while (preg_match("/\/\.\.\//", $newPath)) {
            $newPath = preg_replace("/\/\.\.\//", '/', $newPath);
        }
        return $newPath;
    }
}
