<?php

declare(strict_types=1);

namespace app\core;

use app\common\controller\GlobalController;
use app\core\controller\Actionable;
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

    protected function jsonView(mixed $data, int $code = 200)
    {
        return (new JsonView($data, $code))->output();
    }
}
