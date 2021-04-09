<?php

declare(strict_types=1);

namespace app\api\controller;

use app\api\service\User as UserService;

class User extends Common
{
    protected $user;

    public function initialize()
    {
        $this->user = new UserService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->user->paginatedListAPI($this->request->only($this->user->getAllowHome()));

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->user->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->user->saveAPI($this->request->only($this->user->getAllowSave()));

        return $this->json(...$result);
    }

    public function read($id)
    {
        $result = $this->user->readAPI($id);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $result = $this->user->updateAPI($id, $this->request->only($this->user->getAllowUpdate()));

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->user->deleteAPI($this->request->param('ids'), $this->request->param('type'));

        return $this->json(...$result);
    }
    
    public function restore()
    {
        $result = $this->user->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }
}
