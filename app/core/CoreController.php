<?php

declare(strict_types=1);

namespace app\core;

use app\common\controller\GlobalController;
use app\core\traits\ActionController;
use app\core\traits\AllowField;
use app\core\view\JsonView;

class CoreController extends GlobalController
{
    use ActionController;
    use AllowField;

    protected Facade $facade;

    protected $jsonView;

    public function initialize()
    {
        parent::initialize();
        $this->initFacade();
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

    protected function initJsonView()
    {
        if (isset($this->jsonViewClass) && class_exists($this->jsonViewClass)) {
            $this->jsonView = new $this->jsonViewClass();

            return;
        }
        $this->jsonView = new JsonView();
    }
}
