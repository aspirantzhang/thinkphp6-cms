<?php

declare(strict_types=1);

namespace app\core\domain;

use app\core\BaseModel;
use app\core\exception\SystemException;

class Login
{
    private array $input = [];
    private ?BaseModel $user = null;

    private array $fieldName = [
        'username' => 'admin_name',
        'password' => 'password',
    ];

    public function __construct(protected BaseModel $model)
    {
    }

    public function setFieldName(array $data)
    {
        $this->fieldName = [...$this->fieldName, ...$data];

        return $this;
    }

    public function setInput(array $input)
    {
        $this->input = $input;

        return $this;
    }

    private function checkRequiredInput()
    {
        if ((
            ($this->input[$this->fieldName['username']] ?? false) &&
            ($this->input[$this->fieldName['password']] ?? false)
        ) === false) {
            throw new SystemException('missing required values');
        }
    }

    private function getUser()
    {
        $username = $this->input[$this->fieldName['username']];
        $this->user = $this->model->where($this->fieldName['username'], $username)->find();
    }

    private function isCorrectPassword()
    {
        return password_verify($this->input[$this->fieldName['password']], $this->user->{$this->fieldName['password']});
    }

    private function getPayloadAndToken()
    {
        $payload = [
            'admin_id' => $this->user->id,
            'admin_name' => $this->user->admin_name,
            'display_name' => $this->user->display_name ?? $this->user->admin_name,
        ];
        $token = app('jwt')->getToken($payload);

        return [$payload, $token];
    }

    public function check()
    {
        $this->checkRequiredInput();

        $this->getUser();

        if ($this->user && $this->isCorrectPassword()) {
            [$payload, $token] = $this->getPayloadAndToken();

            return [...$payload, ...$token];
        }

        return false;
    }
}
