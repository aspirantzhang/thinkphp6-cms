<?php
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;

class AuthRule extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id'];
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'rule', 'name', 'type', 'condition', 'status', 'create_time'];
    public $allowList = ['id', 'rule', 'name', 'type', 'condition', 'status' ,'create_time' ,'update_time'];
    public $allowRead = ['id', 'rule', 'name', 'type', 'condition', 'status' ,'create_time' ,'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['rule', 'name', 'type', 'condition' ,'status'];
    public $allowUpdate = ['id', 'rule', 'name', 'type', 'condition', 'status'];
    public $allowSearch = ['id', 'rule', 'name', 'type', 'status', 'create_time'];

    // Accessor

    // Mutator

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
    public function searchRuleAttr($query, $value, $data)
    {
        $query->where('rule', 'like', '%'. $value . '%');
    }
    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%'. $value . '%');
    }
    public function searchTypeAttr($query, $value, $data)
    {
        $query->where('type', $value);
    }
    public function searchStatusAttr($query, $value, $data)
    {
        $query->where('status', $value);
    }
    public function searchCreateTimeAttr($query, $value, $data)
    {
        $timeArray = explode('T', $value);
        if (validateDateTime($timeArray[0]) && validateDateTime($timeArray[1])) {
            $query->whereBetweenTime('create_time', $timeArray[0], $timeArray[1]);
        }
    }



}
