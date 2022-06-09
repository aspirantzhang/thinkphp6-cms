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

    public function __construct(protected array $data)
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
        $this->header = Config::get('response.default_header') ?? $header;
    }

    private function initBody()
    {
        $defaultBody = [
            'success' => false,
            'message' => 'unknown error',
            'data' => [],
        ];

        $this->body = [...$defaultBody, ...$this->data];
        $this->unsetUnnecessaryBody();
    }

    private function unsetUnnecessaryBody()
    {
        unset($this->body['code'], $this->body['header']);
    }

    private function initCode()
    {
        $this->code = $this->data['code'] ?? 200;
    }

    public function output()
    {
        return Response::create($this->body, 'json', $this->code)->header($this->header);
    }
}
