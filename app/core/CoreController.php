<?php

declare(strict_types=1);

namespace app\core;

use app\common\controller\GlobalController;
use app\core\controller\Actionable;
use app\core\exception\SystemException;
use app\core\view\JsonView;

abstract class CoreController extends GlobalController
{
    use Actionable;

    protected CoreFacade $facade;
    protected CoreModel $model;

    public function initialize()
    {
        parent::initialize();
        $this->initFacade();
        $this->initModel();
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

    protected function jsonView(mixed $data, int $code = 200)
    {
        return (new JsonView($data, $code))->output();
    }
}
