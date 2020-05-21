<?php
<<<<<<< HEAD

declare(strict_types=1);
=======
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

namespace app\common\controller;

use think\App;
use think\exception\ValidateException;
use think\Validate;

<<<<<<< HEAD
/**
 * 控制器基础类.
 */
abstract class GlobalController
{
    /**
     * Request实例.
     *
=======

/**
 * 控制器基础类
 */
abstract class GlobalController
{

    /**
     * Request实例
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
     * @var \think\Request
     */
    protected $request;

    /**
<<<<<<< HEAD
     * 应用实例.
     *
=======
     * 应用实例
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
<<<<<<< HEAD
     *
=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
     * @var bool
     */
    protected $batchValidate = false;

    /**
<<<<<<< HEAD
     * 控制器中间件.
     *
=======
     * 控制器中间件
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
     * @var array
     */
    protected $middleware = [];

    /**
<<<<<<< HEAD
     * 构造方法.
     *
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
=======
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

<<<<<<< HEAD
=======


>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    // 初始化
    protected function initialize()
    {
    }

    /**
<<<<<<< HEAD
     * 验证数据.
     *
     * @param array        $data     数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array        $message  提示信息
     * @param bool         $batch    是否批量验证
     *
     * @return array|string|true
     *
=======
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
<<<<<<< HEAD
            $v = new $class();
=======
            $v     = new $class();
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
<<<<<<< HEAD
=======

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
