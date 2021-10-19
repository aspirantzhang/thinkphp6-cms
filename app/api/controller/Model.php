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
        $result = $this->model->treeListAPI($this->request->only($this->model->getAllowHome()));

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
        if ($result[0]['success'] === true) {
            $result[0]['call'] = ['fetchMenu'];
        }

        return $this->json(...$result);
    }

    public function read(int $id)
    {
        $result = $this->model->readAPI($id);

        return $this->json(...$result);
    }

    public function update(int $id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->getAllowUpdate()));

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->model->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        if ($result[0]['success'] === true) {
            $result[0]['call'] = ['fetchMenu'];
        }

        return $this->json(...$result);
    }

    public function design(int $id)
    {
        $result = $this->model->designAPI($id);

        return $this->json(...$result);
    }

    public function designUpdate(int $id)
    {
        $result = $this->model->designUpdateAPI($id, $this->request->param('type'), $this->request->param('data'));

        return $this->json(...$result);
    }

    public function i18nRead(int $id)
    {
        $result = $this->model->i18nReadAPI($id);

        return $this->json(...$result);
    }

    public function i18nUpdate(int $id)
    {
        $result = $this->model->i18nUpdateAPI($id, $this->request->only(Config::get('lang.allow_lang_list')));

        return $this->json(...$result);
    }

    public function revisionHome(int $id)
    {
        $result = $this->app->revision->listAPI($this->model->getTableName(), $id, (int)$this->request->param('page') ?: 1);

        return $this->json($result);
    }

    public function revisionRestore(int $id)
    {
        $result = $this->app->revision->restoreAPI(
            $this->model->getTableName(),
            $id,
            (int)$this->request->param('revisionId'),
            $this->model->getRevisionTable()
        );

        return $this->json($result);
    }

    public function revisionRead(int $revisionId)
    {
        $result = $this->app->revision->readAPI((int)$revisionId);

        return $this->json($result);
    }
}
