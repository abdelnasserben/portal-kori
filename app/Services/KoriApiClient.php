<?php

namespace App\Services;

use App\Exceptions\KoriApiException;
use App\Services\Auth\TokenService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class KoriApiClient
{
    public function __construct(private readonly TokenService $tokens) {}

    public function request(array $headers = []): PendingRequest
    {
        $cfg = config('kori.api');

        $req = Http::baseUrl(rtrim($cfg['base_url'], '/'))
            ->acceptJson()
            ->asJson()
            ->timeout($cfg['timeout'])
            ->retry(
                $cfg['retry']['times'],
                $cfg['retry']['sleep_ms']
            );

        $access = $this->tokens->getValidAccessToken();
        if (is_string($access) && $access !== '') {
            $req = $req->withToken($access);
        }

        if (!empty($headers)) {
            $req = $req->withHeaders($headers);
        }

        return $req;
    }

    public function get(string $uri, array $query = [], array $headers = []): array
    {
        return $this->send(fn() => $this->request($headers)->get($uri, $query), 'GET', $uri);
    }

    public function post(string $uri, array $data = [], array $headers = []): array
    {
        return $this->send(fn() => $this->request($headers)->post($uri, $data), 'POST', $uri);
    }

    public function put(string $uri, array $data = [], array $headers = []): array
    {
        return $this->send(fn() => $this->request($headers)->put($uri, $data), 'PUT', $uri);
    }

    public function delete(string $uri, array $query = [], array $headers = []): array
    {
        return $this->send(fn() => $this->request($headers)->delete($uri, $query), 'DELETE', $uri);
    }

    private function send(callable $fn, string $method, string $uri): array
    {
        try {
            /** @var Response $response */
            $response = $fn();
            return $this->decodeOrThrow($response, $method . ' ' . $uri);
        } catch (KoriApiException $e) {
            throw $e;
        } catch (RequestException $e) {
            $response = $e->response;
            $status = $response?->status() ?? 500;

            $payload = null;
            if ($response) {
                $payload = $this->tryJsonArray($response) ?? [
                    'contentType' => (string) $response->header('Content-Type') ?: null,
                    'bodyPreview' => $this->preview((string) $response->body()),
                ];
            } else {
                $payload = ['exception' => $e::class, 'message' => $e->getMessage()];
            }

            throw new KoriApiException(
                status: $status,
                payload: $payload,
                message: "Kori API request failed ($method $uri)",
                previous: $e
            );
        } catch (ConnectionException $e) {
            throw new KoriApiException(
                status: 503,
                payload: null,
                message: "Kori API unreachable ($method $uri): " . $e->getMessage(),
                previous: $e
            );
        } catch (Throwable $e) {
            throw new KoriApiException(
                status: 500,
                payload: ['exception' => $e::class, 'message' => $e->getMessage()],
                message: "Unexpected HTTP error ($method $uri)",
                previous: $e
            );
        }
    }

    private function decodeOrThrow(Response $response, string $context): array
    {
        $payload = $this->tryJsonArray($response);

        if ($response->failed()) {
            $fallback = $payload ?? [
                'contentType' => (string) $response->header('Content-Type') ?: null,
                'bodyPreview' => $this->preview((string) $response->body()),
            ];

            throw new KoriApiException(
                status: $response->status(),
                payload: $fallback,
                message: "Kori API request failed ($context)",
            );
        }

        return $payload ?? [];
    }

    private function tryJsonArray(Response $response): ?array
    {
        try {
            $json = $response->json();
            return is_array($json) ? $json : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function preview(string $body, int $max = 400): ?string
    {
        $body = trim($body);
        if ($body === '') return null;

        return mb_strlen($body) <= $max ? $body : (mb_substr($body, 0, $max) . '…');
    }
}
