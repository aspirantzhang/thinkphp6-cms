<?php

declare(strict_types=1);

namespace app\api\controller;

use think\facade\Config;
use app\api\service\Model as ModelService;

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
        $result[0]['call'] = ['fetchMenu'];
        
        return $this->json(...$result);
    }

    public function design($id)
    {
        $result = $this->model->designAPI($id);

        return $this->json(...$result);
    }

    public function designUpdate($id)
    {
        $result = $this->model->designUpdateAPI($id, $this->request->param('type'), $this->request->param('data'));

        return $this->json(...$result);
    }

    public function i18n($id)
    {
        $result = $this->model->i18nAPI($id);

        return $this->json(...$result);
    }

    public function i18nUpdate($id)
    {
        $result = $this->model->i18nUpdateAPI($id, $this->request->only(Config::get('lang.allow_lang_list')));

        return $this->json(...$result);
    }
}
