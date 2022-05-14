<?php

declare(strict_types=1);

namespace app\core\validator\rule;

use app\core\validator\CoreRule;

// support number string, number array or single number
class Integer extends CoreRule
{
    public function rule($value): bool
    {
        if (isInt($value)) {
            return true;
        }
        if (is_array($value)) {
            return $this->checkIntegerArray($value);
        }
        if (is_string($value) && strpos($value, ',')) {
            return $this->checkIntegerArray(explode(',', $value));
        }

        return false;
    }

    private function checkIntegerArray(array $value): bool
    {
        foreach ($value as $val) {
            if (!isInt($val)) {
                return false;
            }
        }

        return true;
    }
}
