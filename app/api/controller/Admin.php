<?php

declare(strict_types=1);

namespace app\api\controller;

use app\api\service\Admin as AdminService;
use think\facade\Session;

class Admin extends Common
{
    protected $admin;

    public function initialize()
    {
        $this->admin = new AdminService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->admin->paginatedListAPI($this->request->only($this->admin->getAllowHome()), ['groups']);

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->admin->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->admin->saveAPI($this->request->only($this->admin->getAllowSave()), ['groups']);

        return $this->json(...$result);
    }

    public function read($id)
    {
        $result = $this->admin->readAPI($id, ['groups']);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $result = $this->admin->updateAPI($id, $this->request->only($this->admin->getAllowUpdate()), ['groups']);

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->admin->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $this->json(...$result);
    }

    public function restore()
    {
        $result = $this->admin->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }

    public function login()
    {
        $result = $this->admin->loginAPI($this->request->param());
        $httpBody = $result[0];

        if ($httpBody['success'] === true) {
            $adminId = $httpBody['data']['id'];
            $adminName = $httpBody['data']['admin_name'];
            Session::set('adminId', $adminId);
            Session::set('adminName', $adminName);
        }

        return $this->json(...$result);
    }

    public function logout()
    {
        Session::clear();
        return $this->success();
    }

    public function info()
    {
        if (Session::has('adminId') || $this->request->param('X-API-KEY') === 'antd') {
            $data = [
                "name" => Session::get('adminName') ?? 'API TEST',
                "avatar" => 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png',
                "userid" => '00000001',
                "email" => 'antdesign@alipay.com',
                "signature" => '海纳百川，有容乃大',
                "title" => '交互专家',
                "group" => '蚂蚁金服－某某某事业群－某某平台部－某某技术部－UED',
                "tags" => [
                    [
                        "key" => '0',
                        "label" => '很有想法的',
                    ],
                    [
                        "key" => '1',
                        "label" => '专注设计',
                    ],
                    [
                        "key" => '2',
                        "label" => '辣~',
                    ],
                    [
                        "key" => '3',
                        "label" => '大长腿',
                    ],
                    [
                        "key" => '4',
                        "label" => '川妹子',
                    ],
                    [
                        "key" => '5',
                        "label" => '海纳百川',
                    ],
                ],
                "notifyCount" => 12,
                "unreadCount" => 11,
                "country" => 'China',
                "access" => 'admin',
                "geographic" => [
                    "province" => [
                        "label" => '浙江省',
                        "key" => '330000',
                    ],
                    "city" => [
                        "label" => '杭州市',
                        "key" => '330100',
                    ],
                ],
                "address" => '西湖区工专路 77 号',
                "phone" => '0752-268888888',
            ];

            return $this->json($data);
        } else {
            $notLogin = [
                "data" => [
                    "isLogin" => false,
                ],
                "errorCode" => '401',
                "errorMessage" => '请先登录！',
                "success" => true,
            ];
            return $this->json($notLogin, 401);
        }
    }
}
