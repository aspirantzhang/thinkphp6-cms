<?php

declare(strict_types=1);

namespace app\core;

use app\core\exception\SystemException;
use think\Request;

abstract class BaseFacade
{
    public BaseModel $model;
    protected Request $request;

    protected array $input;
    private bool $customizedInput = false;

    public function __construct()
    {
        $this->request = app('request');
        $this->initModel();
    }

    public function isNull()
    {
        return false;
    }

    protected function initModel()
    {
        $modelClass = str_replace('facade', 'model', static::class);
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();

            return;
        }
        throw new SystemException('model cannot be instantiated in facade layer: ' . $modelClass);
    }

    protected function initInput()
    {
        if ($this->customizedInput === false) {
            $this->input = $this->request->only($this->model->getAllow($this->request->action()));
        }
    }

    public function setInput(array $input)
    {
        $this->customizedInput = true;
        $this->input = $input;

        return $this;
    }
}
