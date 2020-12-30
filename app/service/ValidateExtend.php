<?php

declare(strict_types=1);

namespace app\service;

use think\Validate;

class ValidateExtend extends \think\Service
{
    public function boot()
    {
        Validate::maker(function ($validate) {
            $validate->extend('dateTimeRange', function ($value) {
                if (!$value) {
                    return false;
                }
                if (validateDateTime($value, \DateTime::ATOM)) {
                    return true;
                }
                $valueArray = explode(',', $value);
                if (count($valueArray) === 2 && validateDateTime($valueArray[0], \DateTime::ATOM) && validateDateTime($valueArray[1], \DateTime::ATOM)) {
                    return true;
                } else {
                    return false;
                }
            });

            $validate->extend('numberTag', function ($value) {
                if (strpos($value, ',')) {
                    $arr = explode(',', $value);
                    foreach ($arr as $val) {
                        if (!is_numeric($val)) {
                            return false;
                        }
                    }
                    return true;
                } else {
                    if (is_numeric($value)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            });

            $validate->extend('numberArray', function ($value) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        if (!is_numeric($val)) {
                            return false;
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            });
        });
    }
}
