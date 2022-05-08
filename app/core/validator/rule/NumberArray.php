<?php

declare(strict_types=1);

namespace app\core\validator\rule;

use think\Validate;

// support number string, number array or single number
class NumberArray implements \SplObserver
{
    public function update(\SplSubject | Validate $validate): void
    {
        $validate->extend('NumberArray', function ($value) {
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
        });
    }
}
