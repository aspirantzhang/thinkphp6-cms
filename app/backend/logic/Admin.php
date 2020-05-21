<?php
<<<<<<< HEAD

declare(strict_types=1);
=======
declare (strict_types = 1);
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

namespace app\backend\logic;

use app\backend\model\Admin as AdminModel;

class Admin extends AdminModel
{
<<<<<<< HEAD
=======

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    protected function getListData($data)
    {
        $search = getSearchParam($data, $this->allowSearch);
        $sort = getSortParam($data, $this->allowSort);
        $perPage = getPerPageParam($data);

<<<<<<< HEAD
        
        // return $this->with(['groups' => function ($query) {
        //     $query->field('auth_group.name')->where('auth_group.status', 1)->hidden(['pivot']);
        // }])

        return $this->with(['groups'])
=======
        return $this->with(['groups'=>function($query) {
                        $query->field('auth_group.id, auth_group.name')->where('auth_group.status', 1)->hidden(['pivot']);
                    }])
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                    ->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->paginate($perPage);
    }

    public function saveNew($data)
    {
        $ifExists = $this->withTrashed()->where('username', $data['username'])->find();
        if ($ifExists) {
            $this->error = 'Sorry, that username already exists.';
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            return -1;
        }
        // Display Name default value
        if (!isset($data['display_name'])) {
            $data['display_name'] = $data['username'];
        }
        $result = $this->allowField($this->allowSave)->save($data);
        if ($result) {
            return $this->getData('id');
        } else {
            $this->error = 'Save failed.';
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            return 0;
        }
    }

    public function checkPassword($data)
    {
        $admin = $this->where('username', $data['username'])->where('status', 1)->find();
        if ($admin) {
            return password_verify($data['password'], $admin->password);
        } else {
            return -1;
        }
    }
<<<<<<< HEAD
=======

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
