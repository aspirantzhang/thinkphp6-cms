<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\model\Model as ModelModel;

class Model extends Common
{
    public function initialize()
    {
        parent::initialize();
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
