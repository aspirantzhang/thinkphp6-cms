<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Model as ModelService;
use think\facade\Db;

class Model extends Common
{
    public function initialize()
    {
        $this->model = new ModelService();
        parent::initialize();
    }

    public function home()
    {
        $models = Db::name('model')->where('status', 1)->select()->toArray();
        $models = array_map(function ($models) {
            return array(
                'path' => $models['path'],
                'name' => $models['name'],
                'icon' => $models['icon'],
                'component' => $models['component'],
            );
        }, $models);

        // halt($models);

        return json($models);
    }

    public function save()
    {
        $data = $this->request->param('data');
        // halt($data);
        $model = new ModelModel();
        $model->title = $data['title'];
        $model->name = $data['name'];
        $model->icon = $data['icon'];
        $model->component = $data['component'];
        $model->data = $data;
        $model->save();
        return resSuccess('Add Successfully.');
    }
}
