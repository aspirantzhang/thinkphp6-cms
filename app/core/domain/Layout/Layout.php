<?php

declare(strict_types=1);

namespace app\core\domain\Layout;

use app\core\CoreModel;

abstract class Layout implements \JsonSerializable
{
    protected array $param;

    protected array $option;

    public function __construct(protected CoreModel $model)
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
}
