<?php

namespace D4T\UnFxSdk;

use D4T\UnFxSdk\Exceptions\FailedActionException;
use D4T\UnFxSdk\Exceptions\InvalidDataException;
use D4T\UnFxSdk\Exceptions\UnauthorizedException;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

trait MakesHttpRequests
{
    private int $timeout = 30;

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function get(string $uri)
    {
        return $this->request('GET', $uri);
    }

    public function post(string $uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    public function put(string $uri, array $payload = [])
    {
        return $this->request('PUT', $uri, $payload);
    }

    public function patch(string $uri, array $payload = [])
    {
        return $this->request('PATCH', $uri, $payload);
    }

    public function delete(string $uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    public function request(string $verb, string $uri, array $payload = []): mixed
    {
        $options['timeout'] = $this->timeout;

        if(!empty($payload))
            $options['json'] = $payload;

        $response = $this->client->request(
            $verb,
            $uri,
            $options
        );

        if (! $this->isSuccessful($response)) {
            $this->handleRequestError($response);

            return null;
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    public function isSuccessful($response): bool
    {
        if (! $response) {
            return false;
        }

        return (int) substr($response->getStatusCode(), 0, 1) === 2;
    }

    protected function buildFilterString(array $filters): string
    {
        if (count($filters) === 0) {
            return '';
        }

        $preparedFilters = [];
        foreach ($filters as $name => $value) {
            $preparedFilters["filter[{$name}]"] = $value;
        }

        return '?'.http_build_query($preparedFilters);
    }

    protected function handleRequestError(ResponseInterface $response): void
    {
        if ($response->getStatusCode() === 422) {
            throw new InvalidDataException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() === 404) {
            throw new ResourceNotFoundException();
        }

        if ($response->getStatusCode() === 400) {
            throw new FailedActionException((string) $response->getBody());
        }

        if ($response->getStatusCode() === 401) {
            throw new UnauthorizedException((string) $response->getBody());
        }

        throw new Exception((string) $response->getBody());
    }

}
