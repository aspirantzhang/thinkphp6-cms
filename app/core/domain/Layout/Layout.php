<?php

declare(strict_types=1);

namespace app\core\domain\Layout;

use app\core\model\Model;

abstract class Layout implements \JsonSerializable
{
    protected array $param;

    protected array $option;

    public function __construct(protected Model $model)
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
