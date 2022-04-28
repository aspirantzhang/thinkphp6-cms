<?php

declare(strict_types=1);

namespace app\core\controller;

trait Actionable
{
    public function index()
    {
        $result = $this->facade->getPaginatedList($this->model->getAllowBrowse());

        return $this->jsonView($result);
    }
}
