<?php

declare(strict_types=1);

namespace app\backend\domain\Layout;

abstract class Layout
{
    protected array $param;
    protected array $option;
    protected array $module;
    public function __construct(protected $model)
    {
    }

    public function withParam(array $param)
    {
        $this->param = $param;
        return $this;
    }
    public function withOption(array $option)
    {
        $this->option = $option;
        return $this;
    }
    public function withModule(array $module)
    {
        $this->module = $module;
        return $this;
    }
}
