<?php

declare(strict_types=1);

namespace app\api\model;

use app\common\model\GlobalModel;
use think\model\concern\SoftDelete;
use app\api\traits\Model as ModelTrait;
use app\api\traits\Logic as LogicTrait;
use app\api\traits\Service as ServiceTrait;
use app\api\traits\View as ViewTrait;
use app\api\traits\AllowField as AllowFieldTrait;

class Common extends GlobalModel
{
    use SoftDelete;
    use ModelTrait;
    use LogicTrait;
    use ServiceTrait;
    use ViewTrait;
    use AllowFieldTrait;

    protected $deleteTime = 'delete_time';
    protected $unique;
    
    // Allow fields of AllowFieldTrait
    public $allowHome = [];
    public $allowList = [];
    public $allowSort = [];
    public $allowRead = [];
    public $allowSave = [];
    public $allowUpdate = [];
    public $allowSearch = [];
  
    public function initialize()
    {
        parent::initialize();
    }

    protected function getTableName()
    {
        return parse_name($this->getName());
    }

    public function scopeStatus($query)
    {
        $tableName = $this->getTableName();
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

    public function isTrash($params = [])
    {
        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            return true;
        }
        return false;
    }
}
