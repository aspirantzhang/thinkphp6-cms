<?php

declare(strict_types=1);

namespace app\core;

use app\common\model\GlobalModel;
use app\core\exception\BizException;
use app\core\model\Modular;

abstract class BaseModel extends GlobalModel
{
    use Modular;

    protected $deleteTime = 'delete_time';

    public function checkUniqueValues(array $input, string $field = null)
    {
        $uniqueFields = $this->getUnique();
        foreach ($uniqueFields as $field) {
            if ($input[$field] ?? false) {
                $result = $this->where($field, '=', $input[$field])->find();
                if ($result) {
                    throw new BizException('value already exists: ' . $field);
                }
            }
        }
    }
}
