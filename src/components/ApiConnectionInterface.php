<?php

namespace hipanel\components;

interface ApiConnectionInterface
{
    /**
     * Performs POST HTTP request.
     * @param string $resource Resource location
     * @param array $query query options
     * @param string $payload request payload
     * @param bool $raw Do not try to decode data, event when response is decodeable (JSON). Defaults to `false`
     * @return mixed response
     */
    public function post($resource, $query = [], $payload = null, $raw = false);

    /**
     * Performs GET HTTP request.
     * @param string $resource Resource location
     * @param array $query query options
     * @param bool $raw Do not try to decode data, event when response is decodeable (JSON). Defaults to `false`
     * @return mixed response
     */
    public function get($resource, $query = [], $raw = false);
}
