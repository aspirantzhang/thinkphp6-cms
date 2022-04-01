<?php

declare(strict_types=1);

namespace app\core;

use app\common\controller\GlobalController;
use app\core\exception\SystemException;
use app\core\model\Model;
use app\core\traits\ActionController;
use app\core\view\JsonView;
use think\db\Query;

class CoreController extends GlobalController
{
    use ActionController;

    protected Facade $facade;
    protected Model | Query $model;

    protected $jsonView;

    public function initialize()
    {
        parent::initialize();
        $this->initFacade();
        $this->initModel();
        $this->initJsonView();
    }

    protected function initFacade()
    {
        $facadeClass = str_replace('controller', 'facade', static::class);
        if (class_exists($facadeClass)) {
            $this->facade = new $facadeClass();

            return;
        }
        $this->facade = new \app\core\facade\NullFacade();
    }

    protected function initModel()
    {
        $modelClass = str_replace('controller', 'model', static::class);
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();

            return;
        }
        throw new SystemException('model cannot be instantiated: ' . $modelClass);
    }

    protected function initJsonView()
    {
        if (isset($this->jsonViewClass) && class_exists($this->jsonViewClass)) {
            $this->jsonView = new $this->jsonViewClass();

            return;
        }
        $this->jsonView = new JsonView();
    }
}
