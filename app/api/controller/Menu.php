<?php

declare(strict_types=1);

namespace app\api\controller;

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
/*         // for API
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted); */
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
/*         // for API
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted); */
        $result = $this->menu->updateAPI($id, $this->request->only($this->menu->getAllowUpdate()));

        return $this->json(...$result);
    }

    public function delete()
    {
/*         // for API
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted); */
        $result = $this->menu->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $this->json(...$result);
    }

    public function restore()
    {
/*         // for API
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted); */
        $result = $this->menu->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }

    public function backend()
    {
        sleep(3);
        $result = $this->menu->treeDataAPI(['order' => 'asc']);

        return $this->json($result);
    }
}
