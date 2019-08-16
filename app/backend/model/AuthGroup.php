<?php
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;

class AuthGroup extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id'];
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'name', 'rules', 'status', 'create_time'];
    public $allowList = ['id', 'name', 'rules', 'status' ,'create_time' ,'update_time'];
    public $allowRead = ['id', 'name', 'rules', 'status' ,'create_time' ,'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['name', 'status'];
    public $allowUpdate = ['id', 'name', 'status'];
    public $allowSearch = ['id', 'name', 'status', 'create_time'];

    // Accessor

    // Mutator

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%'. $value . '%');
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
