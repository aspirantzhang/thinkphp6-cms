<?php
<<<<<<< HEAD

declare(strict_types=1);
=======
declare (strict_types = 1);
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

namespace app\backend\model;

use app\common\model\GlobalModel;

class Common extends GlobalModel
{
    public function initialize()
    {
        parent::initialize();
    }

<<<<<<< HEAD
    public function success($message, $uri = '')
    {
        return [
            'status' => 'success',
            'msg' => $message,
            'uri' => $uri,
        ];
    }

    public function error($message, $uri = '')
    {
        return [
            'status' => 'error',
            'msg' => $message,
            'uri' => $uri,
        ];
    }
=======
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

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
