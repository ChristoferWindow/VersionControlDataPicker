<?php

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ApiClient
 */
interface ApiClient
{
    public function query(string $url, string $method): ResponseInterface;
}