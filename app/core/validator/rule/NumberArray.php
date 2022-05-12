<?php

declare(strict_types=1);

namespace app\core\validator\rule;

use app\core\validator\CoreRule;

// support number string, number array or single number
class NumberArray extends CoreRule
{
    public function rule($value): bool
    {
        if (isInt($value)) {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $val) {
                if (!isInt($val)) {
                    return false;
                }
            }

            return true;
        }
        if (is_string($value) && strpos($value, ',')) {
            foreach (explode(',', $value) as $val) {
                if (!isInt($val)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
