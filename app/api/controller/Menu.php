<?php

declare(strict_types=1);

namespace app\api\controller;

use think\facade\Config;
use app\api\service\Menu as MenuService;

class Menu extends Common
{
    protected $menu;

    public function initialize()
    {
        $this->menu = new MenuService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->menu->treeListAPI($this->request->only($this->menu->getAllowHome()));

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->menu->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->menu->saveAPI($this->request->only($this->menu->getAllowSave()));

        return $this->json(...$result);
    }

    public function read(int $id)
    {
        $result = $this->menu->readAPI($id);

        return $this->json(...$result);
    }

    public function update(int $id)
    {
        $result = $this->menu->updateAPI($id, $this->request->only($this->menu->getAllowUpdate()));

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->menu->deleteAPI($this->request->param('ids'), $this->request->param('type'));

        return $this->json(...$result);
    }

    public function restore()
    {
        $result = $this->menu->restoreAPI($this->request->param('ids'));

        return $this->json(...$result);
    }

    public function backend()
    {
        $result = $this->menu->treeDataAPI(['order' => 'asc']);

        return $this->json($result);
    }

    public function i18nRead(int $id)
    {
        $result = $this->menu->i18nReadAPI($id);

        return $this->json(...$result);
    }

    public function i18nUpdate(int $id)
    {
        $result = $this->menu->i18nUpdateAPI($id, $this->request->only(Config::get('lang.allow_lang_list')));

        return $this->json(...$result);
    }

    public function revisionHome(int $id)
    {
        $result = $this->app->revision->listAPI($this->menu->getTableName(), $id, (int)$this->request->param('page') ?: 1);

        return $this->json($result);
    }

    public function revisionRestore(int $id)
    {
        $result = $this->app->revision->restoreAPI(
            $this->menu->getTableName(),
            $id,
            (int)$this->request->param('revisionId'),
            $this->menu->getRevisionTable()
        );

        return $this->json($result);
    }

    public function revisionRead(int $revisionId)
    {
        $result = $this->app->revision->readAPI((int)$revisionId);

        return $this->json($result);
    }
}
