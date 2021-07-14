<?php

namespace App\Services;

/*
 * Get url parts
 *
 * Scheme - https
 * Host   - www.heyharmon.com
 * Domain - heyharmon.com
 * Path   - /foo/bar
 */
class UrlService {

    /*
     * Get all url parts
     */
    public function getAll($url)
    {
        return $url_parts = [
            'scheme' => $this->getScheme($url),
            'host'   => $this->getHost($url),
            'domain' => $this->getDomain($url),
            'path'   => $this->getPath($url),
        ];
    }

    /*
     * Get url scheme
     *
     * e.g., "https"
     */
    public static function getScheme($url)
    {
        return parse_url($url)['scheme'];
    }

    /*
     * Get url host
     *
     * e.g., "www.heyharmon.com"
     */
    public static function getHost($url)
    {
        return parse_url($url)['host'];
    }

    /*
     * Get url domain
     *
     * e.g., "heyharmon.com"
     */
    public static function getDomain($url)
    {
        // Extract the domain
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', parse_url($url)['host'], $regs)) {

            // Return the domain
            return $regs['domain'];

        }

        // No domain in $url
        return '';
    }

    /*
     * Get url path
     *
     * e.g., "/foo/bar"
     */
    public static function getPath($url)
    {
        return parse_url($url)['host'];
    }

}
