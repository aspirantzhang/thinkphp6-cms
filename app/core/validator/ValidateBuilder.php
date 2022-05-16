<?php

declare(strict_types=1);

namespace app\core\validator;

use think\Validate;

class ValidateBuilder extends Validate
{
    public function __construct(private $module)
    {
        parent::__construct();
        $this->rule['admin_name'] = 'required';
        $this->scene['index'] = ['admin_name'];
        $this->message['admin_name.required'] = 'octopus-required';
    }
}
