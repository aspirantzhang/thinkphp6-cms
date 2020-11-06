<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Menu as MenuService;

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

    public function read($id)
    {
        $result = $this->menu->readAPI($id);
        
        return $this->json(...$result);
    }

    public function update($id)
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

        return json($result);
    }
}
