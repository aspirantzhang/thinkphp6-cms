<?php

declare(strict_types=1);

namespace app\core\validator\rule;

use app\core\validator\CoreRule;
use think\Validate;

class DateTimeRange implements CoreRule
{
    public function handle(Validate $validate)
    {
        $validate->extend('DateTimeRange', function ($value) {
            return $this->check($value);
        });
    }

    public function check($value): bool
    {
        if (!$value) {
            return false;
        }

        if (str_contains($value, ',')) {
            $valueArray = explode(',', $value);

            return $this->isValidDatetimeRange($valueArray);
        } else {
            return $this->isValidDatetime($value);
        }
    }

    private function isValidDatetime($value)
    {
        if ($this->isValidDateTimeFormat($value, \DateTime::ATOM)) {
            return true;
        }

        return false;
    }

    private function isValidDatetimeRange(array $valueArray)
    {
        if (count($valueArray) === 2 &&
            $this->isValidDateTimeFormat($valueArray[0], \DateTime::ATOM) &&
            $this->isValidDateTimeFormat($valueArray[1], \DateTime::ATOM)) {
            return true;
        } else {
            return false;
        }
    }

    private function isValidDateTimeFormat($date, $format = 'Y-m-d H:i:s')
    {
        if ($date) {
            $d = \DateTime::createFromFormat($format, $date);

            return $d && $d->format($format) == $date;
        }

        return false;
    }
}
