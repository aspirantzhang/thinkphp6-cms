<?php

declare(strict_types=1);

namespace app\backend\model;

use app\common\model\GlobalModel;
use think\model\concern\SoftDelete;
use app\backend\traits\Model as ModelTrait;
use app\backend\traits\Logic as LogicTrait;
use app\backend\traits\Service as ServiceTrait;
use app\backend\traits\View as ViewTrait;

class Common extends GlobalModel
{
    use SoftDelete;
    use ModelTrait;
    use LogicTrait;
    use ServiceTrait;
    use ViewTrait;

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

    public function success(string $message = '', array $data = [], array $header = [])
    {
        $httpBody = ['success' => true, 'message' => $message, 'data' => $data];
        return [$httpBody, 200, $header];
    }

    public function error(string $message = '', array $data = [], array $header = [])
    {
        $httpBody = ['success' => false, 'message' => $message, 'data' => $data];
        return [$httpBody, 200, $header];
    }
}
