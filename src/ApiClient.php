<?php

use Psr\Http\Message\ResponseInterface;

interface ApiClient
{
    public function query(string $url, string $method): ResponseInterface;
}