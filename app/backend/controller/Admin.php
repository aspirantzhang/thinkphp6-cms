<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\model\Admin as AdminModel;

class Admin extends Common
{

    public function index(AdminModel $adminModel)
    {
        $admin = $adminModel->list($this->request->param());
        return json($admin);
    }

    public function save(AdminModel $adminModel)
    {
        $result = $adminModel->saveNew($this->request->only(['username', 'password', 'display_name', 'status']));
        if ($result == -1) {
            return json(['code'=>'4091', 'error'=> $adminModel->getError()], 409);
        } elseif ($result == 0) {
            return json(['code'=>'4001', 'error'=> $adminModel->getError()], 400);
        } else {
            return json()->code(201);
        }
    }

    public function read($id)
    {
        $admin = AdminModel::find($id);
        if ($admin) {
            return json($admin->hidden(['password', 'delete_time']), 200);
        } else {
            return json(['code'=>'4041', 'error'=>'Admin not found.'], 404);
        }
    }

    public function update($id)
    {
        $admin = AdminModel::where('id', $id)->update($this->request->only(['password', 'display_name', 'status']));
        if ($admin) {
            return json()->code(204);
        } else {
            return json(['code'=>'4003', 'error'=>'Update failed.'], 400);
        }
    }


    public function delete(AdminModel $adminModel, $id)
    {
        if ($adminModel->deleteByID($id)) {
            return json()->code(204);
        } else {
            return json(['code'=>'4004', 'error'=>'Delete failed.'], 400);
        }
    }
}
