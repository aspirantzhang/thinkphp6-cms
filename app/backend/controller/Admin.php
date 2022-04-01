<?php

declare(strict_types=1);

namespace app\backend\controller;

class Admin extends Common
{
    public function initialize()
    {
        parent::initialize();
    }

    public function home()
    {
        $result = $this->facade->getPaginatedList($this->model->getAllowBrowse(), []);
        $this->jsonView->render($result);
    }
}
