<?php
declare(strict_types=1);

namespace Sangho;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Sangho\Exception;

class HttpClient
{
    private Client $guzzle;
    public readonly string $keyType; // 'public' | 'secret'

    private const RETRY_DELAYS = [0.5, 1.0, 2.0]; // seconds
    private const RETRY_CODES = [429, 500, 502, 503, 504];
    private const VALID_PREFIXES = ['pk_live_', 'sk_live_', 'pk_test_', 'sk_test_'];

    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://api.sangho.com/v1',
        int $timeout = 30
    ) {
        $validPrefix = array_filter(self::VALID_PREFIXES, fn($p) => str_starts_with($apiKey, $p));
        if (empty($validPrefix)) {
            throw new \InvalidArgumentException(
                'Invalid API key format. Expected prefix: pk_live_, sk_live_, pk_test_, sk_test_'
            );
        }

        $this->keyType = str_starts_with($apiKey, 'pk_') ? 'public' : 'secret';
        $this->guzzle = new Client([
            'base_uri' => rtrim($this->baseUrl, '/') . '/',
            'timeout' => $timeout,
            'headers' => [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'sangho-php/1.0.0',
            ],
            'http_errors' => false,
        ]);
    }

    public function assertSecretKey(string $method): void
    {
        if ($this->keyType === 'public') {
            throw new Exception\SanghoPublicKeyException(
                "Method `{$method}` requires a secret key (sk_…). You provided a public key (pk_…).",
                'public_key_not_allowed',
                403
            );
        }
    }

    public function get(string $path, array $params = []): array
    {
        return $this->request('GET', $path, ['query' => array_filter($params, fn($v) => $v !== null)]);
    }

    public function post(string $path, array $body = []): ?array
    {
        $idempotencyKey = \Ramsey\Uuid\Uuid::uuid4()->toString();
        return $this->request('POST', $path, [
            'json' => $body,
            'headers' => ['Idempotency-Key' => $idempotencyKey],
        ]);
    }

    public function patch(string $path, array $body): array
    {
        return $this->request('PATCH', $path, ['json' => $body]);
    }

    public function delete(string $path): void
    {
        $this->request('DELETE', $path);
    }

    public function options(string $path): array
    {
        return $this->request('OPTIONS', $path);
    }

    private function request(string $method, string $path, array $opts = []): mixed
    {
        $path = ltrim($path, '/');
        $delays = self::RETRY_DELAYS;
        $attempt = 0;

        while (true) {
            try {
                $resp = $this->guzzle->request($method, $path, $opts);
                $statusCode = $resp->getStatusCode();

                if (in_array($statusCode, self::RETRY_CODES, true) && $attempt < count($delays)) {
                    usleep((int) ($delays[$attempt] * 1_000_000));
                    $attempt++;
                    continue;
                }

                if ($statusCode === 204) {
                    return null;
                }

                $data = json_decode(
                    $resp->getBody()->getContents(),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

                if ($statusCode >= 200 && $statusCode < 300) {
                    return $data;
                }

                $this->raiseForStatus($statusCode, $data, $resp);
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $r = $e->getResponse();
                    $data = json_decode($r->getBody()->getContents(), true) ?? [];
                    $this->raiseForStatus($r->getStatusCode(), $data, $r);
                }
                throw new Exception\SanghoException(
                    $e->getMessage(),
                    'network_error',
                    0,
                    [],
                    $e
                );
            }
        }
    }

    private function raiseForStatus(int $status, array $data, mixed $response): never
    {
        $message = $data['message'] ?? $data['detail'] ?? 'API error';
        if (is_array($message)) {
            $message = implode(' | ', $message);
        }
        $code = $data['code'] ?? null;

        match (true) {
            $status === 401 => throw new Exception\SanghoAuthException($message, 'authentication_error', 401, $data),
            $status === 403 && $code === 'public_key_not_allowed'
            => throw new Exception\SanghoPublicKeyException($message, $code, 403, $data),
            $status === 403 => throw new Exception\SanghoPermissionException($message, 'permission_denied', 403, $data),
            $status === 404 => throw new Exception\SanghoNotFoundException($message, 'not_found', 404, $data),
            $status === 409 => throw new Exception\SanghoIdempotencyException('Idempotency key conflict.', 'idempotency_conflict', 409, $data),
            $status === 422 => throw new Exception\SanghoValidationException($message, 'validation_error', 422, $data),
            $status === 429 => throw new Exception\SanghoRateLimitException(
                (int) ($data['retry_later'] ?? ($response instanceof \Psr\Http\Message\ResponseInterface ? (int) $response->getHeaderLine('Retry-After') : 60) ?: 60)
            ),
            default => throw new Exception\SanghoException($message, 'api_error', $status, $data),
        };
    }
}
