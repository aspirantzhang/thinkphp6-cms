<?php
declare (strict_types = 1);

namespace app\backend\model;

use app\common\model\GlobalModel;

class Common extends GlobalModel
{
    public function initialize()
    {
        parent::initialize();
    }

    public function success($message, $uri='')
    {
        return [
            'status'    =>  'success',
            'msg'       =>  $message,
            'uri'       =>  $uri
        ];
    }

    public function error($message, $uri='')
    {
        return [
            'status'    =>  'error',
            'msg'       =>  $message,
            'uri'       =>  $uri
        ];
    }

}
