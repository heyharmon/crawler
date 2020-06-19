<?php

namespace App\Services\UrlParser;

class ParserService {

    public $parsed_url;

    function __construct($url) {

        $this->parsed_url = parse_url($url);

    }

    /*
     * Get url parts
     *
     * Scheme - https
     * Host   - www.heyharmon.com
     * Domain - heyharmon.com
     * Path   - /foo/bar
     */
    public function parseUrl()
    {

        return $url_parts = [
            'scheme' => $this->getScheme(),
            'host'   => $this->getHost(),
            'domain' => $this->getDomain(),
            'path'   => $this->getPath(),
        ];

    }

    /*
     * Get scheme from parsed url
     *
     * e.g., https
     */
    public function getScheme()
    {

        if (isset($this->parsed_url['scheme'])) {

            return $this->parsed_url['scheme'];

        }

        return '';

    }

    /*
     * Get host from parsed url
     *
     * e.g., www.heyharmon.com
     */
    public function getHost()
    {

        if (isset($this->parsed_url['host'])) {

            return $this->parsed_url['host'];

        }

        return '';

    }

    /*
     * Get domain from parsed url
     *
     * e.g., heyharmon.com
     */
    public function getDomain()
    {

        // Get the host e.g., www.heyharmon.com
        $host = isset($this->parsed_url['host']) ? $this->parsed_url['host'] : '';

        // Extract the domain
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $host, $regs)) {

            // Return the domain
            return $regs['domain'];

        }

        // No domain in $url
        return '';

    }

    /*
     * Get path from parsed url
     *
     * e.g., /foo/bar
     */
    public function getPath()
    {

        if (isset($this->parsed_url['path'])) {

            return $this->parsed_url['path'];

        }

        return '';

    }

}
