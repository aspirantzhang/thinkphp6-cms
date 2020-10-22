<?php

declare(strict_types=1);

namespace app\backend\model;

use app\common\model\GlobalModel;
use think\model\concern\SoftDelete;
use app\backend\traits\Model as ModelTrait;
use app\backend\traits\Logic as LogicTrait;
use app\backend\traits\Service as ServiceTrait;
use app\backend\traits\View as ViewTrait;
use app\backend\traits\AllowField as AllowFieldTrait;

class Common extends GlobalModel
{
    use SoftDelete;
    use ModelTrait;
    use LogicTrait;
    use ServiceTrait;
    use ViewTrait;
    use AllowFieldTrait;

    protected $deleteTime = 'delete_time';
  
    public function initialize()
    {
        parent::initialize();
    }

    public function scopeStatus($query)
    {
        $tableName = parse_name($this->name);
        $query->where($tableName . '.status', 1);
    }

    public function success(string $message = '', array $data = [], array $header = [], $addition = [])
    {
        $httpBody = ['success' => true, 'message' => $message, 'data' => $data];
        $httpBody = array_merge($httpBody, $addition);
        return [$httpBody, 200, $header];
    }

    public function error(string $message = '', array $data = [], array $header = [], $addition = [])
    {
        $httpBody = ['success' => false, 'message' => $message, 'data' => $data];
        $httpBody = array_merge($httpBody, $addition);
        return [$httpBody, 200, $header];
    }
}
