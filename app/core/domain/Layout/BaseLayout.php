<?php

declare(strict_types=1);

namespace app\core\domain\Layout;

use app\core\BaseModel;

abstract class BaseLayout
{
    protected array $input;

    protected array $option;

    public function __construct(protected BaseModel $model)
    {
    }

    public function setInput(array $input)
    {
        $this->input = $input;

        return $this;
    }

    public function setOption(array $option)
    {
        $this->option = $option;

        return $this;
    }
}
