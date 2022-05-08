<?php

declare(strict_types=1);

namespace app\core\validator\rule;

use think\Validate;

class DateTimeRange implements \SplObserver
{
    public function update(\SplSubject | Validate $validate): void
    {
        $validate->extend('DateTimeRange', function ($value) {
            if (!$value) {
                return false;
            }

            if ($this->isValidDateTime($value, \DateTime::ATOM)) {
                return true;
            }

            $valueArray = explode(',', $value);

            if (count($valueArray) === 2 &&
                $this->isValidDateTime($valueArray[0], \DateTime::ATOM) &&
                $this->isValidDateTime($valueArray[1], \DateTime::ATOM)) {
                return true;
            } else {
                return false;
            }
        });
    }

    private function isValidDateTime($date, $format = 'Y-m-d H:i:s')
    {
        if ($date) {
            $d = \DateTime::createFromFormat($format, $date);

            return $d && $d->format($format) == $date;
        }

        return false;
    }
}
