<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Model as ModelService;

// use think\facade\Db;

class Model extends Common
{
    public function initialize()
    {
        $this->model = new ModelService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->model->paginatedListAPI($this->request->only($this->model->allowHome));

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->model->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->model->saveAPI($this->request->only($this->model->allowSave));
        halt($result->success);

        return $this->json(...$result);
    }

    public function read($id)
    {
        $result = $this->model->readAPI($id);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->allowUpdate));

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->model->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $this->json(...$result);
    }

    public function restore()
    {
        $result = $this->model->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }
    
    public function design($id)
    {
        $result = $this->model->designAPI($id);

        return $this->json(...$result);
    }
    
    public function designUpdate($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->allowUpdate));

        return $this->json(...$result);
    }
}
