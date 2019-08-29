<?php
declare (strict_types = 1);

namespace app\backend\logic;

use app\backend\model\AuthRule as AuthRuleModel;

class AuthRule extends AuthRuleModel
{

    protected function getListData($data)
    {
        $search = getSearchParam($data, $this->allowSearch);
        $sort = getSortParam($data, $this->allowSort);
        $perPage = getPerPageParam($data);

        return $this->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->paginate($perPage);
    }

    public function saveNew($data)
    {
        $ifExists = $this->withTrashed()->where('rule', $data['rule'])->find();
        if ($ifExists) {
            $this->error = 'Sorry, that rule already exists.';
            return -1;
        }
        $result = $this->allowField($this->allowSave)->save($data);
        if ($result) {
            return $this->getData('id');
        } else {
            $this->error = 'Save failed.';
            return 0;
        }
    }

}
