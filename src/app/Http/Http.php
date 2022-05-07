<?php

namespace App\Http;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Http
{
    public Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Issue a GET request to the given URL.
     *
     * @param string $url
     *
     * @return Crawler
     */
    public static function get(string $url): Crawler
    {
        return (new self())->send($url);
    }

    /**
     * Send the request to the given URL.
     *
     * @param string $url
     * @param string $method
     *
     * @return Crawler
     */
    public function send(string $url, string $method = 'GET'): Crawler
    {
        return $this->client->request(strtoupper($method), $url);
    }
}
