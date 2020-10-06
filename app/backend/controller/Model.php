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

    // public function home()
    // {
    //     $models = Db::name('model')->where('status', 1)->select()->toArray();
    //     $models = array_map(function ($models) {
    //         return array(
    //             'path' => $models['path'],
    //             'name' => $models['name'],
    //             'icon' => $models['icon'],
    //             'component' => $models['component'],
    //         );
    //     }, $models);

    //     // halt($models);

    //     return json($models);
    // }

    // public function save()
    // {
    //     $data = $this->request->param('data');
    //     // halt($data);
    //     $model = new ModelModel();
    //     $model->title = $data['title'];
    //     $model->name = $data['name'];
    //     $model->icon = $data['icon'];
    //     $model->component = $data['component'];
    //     $model->data = $data;
    //     $model->save();
    //     return resSuccess('Add Successfully.');
    // }
}
