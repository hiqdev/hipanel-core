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
    public function post($resource, $query, $payload, $raw = false);
}
