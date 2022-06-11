<?php

declare(strict_types=1);

namespace app\core\domain\Login;

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

    public function getUser()
    {
        return $this->user;
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
        $result = missingRequiredValues($this->input, [$this->fieldName['username'], $this->fieldName['password']]);
        if ($result !== false) {
            throw new SystemException('missing required values: ' . $result);
        }
    }

    private function getUserModel()
    {
        $username = $this->input[$this->fieldName['username']];
        $this->user = $this->model->where($this->fieldName['username'], $username)->find();
    }

    private function isCorrectPassword()
    {
        return password_verify($this->input[$this->fieldName['password']], $this->user->{$this->fieldName['password']});
    }

    public function check()
    {
        $this->checkRequiredInput();

        $this->getUserModel();

        if ($this->user && $this->isCorrectPassword()) {
            return true;
        }

        return false;
    }

    public function accept(LoginVisitor $visitor)
    {
        $visitor->visitLogin($this);
    }
}
