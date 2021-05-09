<?php

declare(strict_types=1);

namespace app\api\controller;

use app\api\service\Model as ModelService;
use think\facade\Db;
use think\facade\Config;
use think\facade\Console;
use app\api\service\AuthRule as RuleService;
use app\api\service\Menu as MenuService;
use think\helper\Str;

class Model extends Common
{
    protected $model;

    public function initialize()
    {
        $this->model = new ModelService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->model->paginatedListAPI($this->request->only($this->model->getAllowHome()));

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->model->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->model->saveAPI($this->request->only($this->model->getAllowSave()));

        return $this->json(...$result);
    }

    public function read($id)
    {
        $result = $this->model->readAPI($id);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->getAllowUpdate()));

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->model->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $this->json(...$result);
    }

    public function design($id)
    {
        $result = $this->model->designAPI($id);

        return $this->json(...$result);
    }

    public function designUpdate($id)
    {
        $result = $this->model->designUpdateAPI($id, $this->request->param('data'));

        return $this->json(...$result);

        // Build fields sql statement.
        $data = $this->request->param('data');
        if (!empty($data)) {
            $result = $this->model->updateAPI($id, $this->request->only($this->model->getAllowUpdate()));
            return $this->json(...$result);
        }
        return $this->error('Nothing to do.');
    }
}
