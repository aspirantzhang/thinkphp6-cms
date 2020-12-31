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
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted);
    }

    public function read($id)
    {
        $result = $this->menu->readAPI($id);
        
        return $this->json(...$result);
    }

    public function update($id)
    {
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted);
    }

    public function delete()
    {
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted);
    }

    public function restore()
    {
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted);
    }

    public function backend()
    {
        $result = $this->menu->treeDataAPI(['order' => 'asc']);

        return $this->json($result);
    }
}
