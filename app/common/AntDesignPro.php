<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangyajun <448901948@qq.com>
// +----------------------------------------------------------------------

namespace app\common;

use think\Paginator;

/**
 * AntDesignPro 分页接口驱动
 */
class AntDesignPro extends Paginator
{
    public function toArray(): array
    {
        try {
            $total = $this->total();
        } catch (DomainException $e) {
            $total = null;
        }

        return [
            'list'          => $this->items->toArray(),
            'pagination'    =>  [
                    'total'     => $total,
                    'pageSize'  => $this->listRows(),
                    'current'   => $this->currentPage(),
            ],
        ];
    }
    public function render(){

    }
}
