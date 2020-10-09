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

        return $result;
    }

    public function add()
    {
        $result = $this->model->addAPI();

        return $result;
    }

    public function save()
    {
        $result = $this->model->saveAPI($this->request->only($this->model->allowSave));

        return $result;
    }

    public function read($id)
    {
        return $this->model->readAPI($id);
    }

    public function update($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->allowUpdate));

        return $result;
    }

    public function delete()
    {
        $result = $this->model->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $result;
    }

    public function restore()
    {
        $result = $this->model->restoreAPI($this->request->param('ids'));
        
        return $result;
    }
    
    public function design($id)
    {
        return $this->model->designAPI($id);
    }
    
    public function designUpdate($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->allowUpdate));

        return $result;
    }
}
