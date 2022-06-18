<?php

declare(strict_types=1);

namespace app\core\view;

use think\facade\Config;
use think\Response;

class JsonView
{
    private array $body;
    private array $header;
    private int $code;

    public function __construct(protected array $response)
    {
        $this->initHeader();
        $this->initCode();
        $this->initBody();
    }

    public function initHeader()
    {
        $header = [
            'access-control-allow-origin' => '*',
            'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
            'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
            'access-control-allow-credentials' => 'true',
        ];
        $this->header = [...$header, ...Config::get('response.default_header') ?? [], ...$this->response['header'] ?? []];
    }

    private function initBody()
    {
        $defaultBody = [
            'success' => false,
            'message' => 'unknown error',
            'data' => [],
        ];

        $this->body = [...$defaultBody, ...$this->response];
        $this->unsetUnnecessaryBody();
    }

    private function unsetUnnecessaryBody()
    {
        unset($this->body['code'], $this->body['header']);
    }

    private function initCode()
    {
        $this->code = $this->response['code'] ?? 200;
    }

    public function output()
    {
        return Response::create($this->body, 'json', $this->code)->header($this->header);
    }

    public static function buildResponse(bool $success = true, string $message = '', array $data = [], int $code = null, array $header = null)
    {
        $result = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        if ($code) {
            $result['code'] = $code;
        }
        if ($header) {
            $result['header'] = $header;
        }

        return $result;
    }
}
