<?php

declare(strict_types=1);

namespace app\service;

use think\facade\Lang;
use think\facade\Request;
use think\Validate;

class ValidateExtend extends \think\Service
{
    public function boot()
    {
        Validate::maker(function ($validate) {
            foreach (glob(createPath(base_path(), 'api', 'lang', 'validate', Lang::getLangSet(), '*') . '.php') as $filename) {
                Lang::load($filename);
            }

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
            /*
             * support number string, number array or single number
             */
            $validate->extend('numberArray', function ($value) {
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
            $validate->extend('checkParentId', function ($value) {
                return $value !== Request::param('id');
            });
        });
    }
}
