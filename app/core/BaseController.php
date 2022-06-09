<?php

declare(strict_types=1);

namespace app\core;

use app\common\controller\GlobalController;
use app\core\controller\Actionable;
use app\core\view\JsonView;

abstract class BaseController extends GlobalController
{
    use Actionable;

    protected BaseFacade $facade;
    protected BaseModel $model;

    public function initialize()
    {
        parent::initialize();
        $this->initFacade();
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

    protected function jsonView(array $data)
    {
        return (new JsonView($data))->output();
    }
}
