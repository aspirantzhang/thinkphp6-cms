<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Admin as AdminService;
use think\facade\Session;

class Admin extends Common
{
    protected $admin;

    public function initialize()
    {
        $this->admin = new AdminService();
        parent::initialize();
    }

    /** @OA\Schema(
    *    schema="base",
    *    @OA\Property(
    *        property="success",
    *        type="boolean",
    *    ),
    *    @OA\Property(
    *        property="message",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="data",
    *        type="object",
    *        allOf={
    *           @OA\Schema(ref="#/components/schemas/data"),
    *        }
    *    ),
    * )
    */

    /** @OA\Schema(
    *    schema="data",
    *    @OA\Property(
    *        property="page",
    *        type="object",
    *        allOf={
    *           @OA\Schema(
    *               @OA\Property(
    *                   property="title",
    *                   type="string",
    *               ),
    *               @OA\Property(
    *                   property="type",
    *                   type="string",
    *               ),
    *               @OA\Property(
    *                   property="searchBar",
    *                   type="boolean",
    *               ),
    *               @OA\Property(
    *                   property="trash",
    *                   type="boolean",
    *               ),
    *           ),
    *        },
    *    ),
    *    @OA\Property(
    *        property="layout",
    *        type="object",
    *        allOf={
    *           @OA\Schema(
    *               @OA\Property(
    *                   property="tableColumn",
    *                   type="object",
    *               ),
    *               @OA\Property(
    *                   property="tableToolBar",
    *                   type="object",
    *               ),
    *               @OA\Property(
    *                   property="batchToolBar",
    *                   type="object",
    *               ),
    *           ),
    *        },
    *    ),
    *    @OA\Property(
    *        property="dataSource",
    *        type="object",
    *    ),
    *    @OA\Property(
    *        property="meta",
    *        type="object",
    *        allOf={
    *           @OA\Schema(
    *               @OA\Property(
    *                   property="total",
    *                   type="number",
    *               ),
    *               @OA\Property(
    *                   property="per_page",
    *                   type="number",
    *               ),
    *               @OA\Property(
    *                   property="page",
    *                   type="number",
    *               ),
    *           ),
    *        },
    *    ),
    * )
    */
    
    /** @OA\Schema(
    *    schema="admin",
    *    @OA\Property(
    *        property="id",
    *        type="integer",
    *        format="int32",
    *    ),
    *    @OA\Property(
    *        property="username",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="password",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="display_name",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="create_time",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="update_time",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="status",
    *        type="integer",
    *        example=1,
    *        @OA\Items(
    *            type="integer",
    *            enum = {0, 1},
    *        )
    *    ),
    * )
    */

    /**
    * @OA\Get(
    *     path="/backend/admins",
    *     summary="Get the paginated list of administrators.",
    *     tags={"admins"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Parameter(
    *         name="page",
    *         in="query",
    *         description="Current page number",
    *         example=1,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Parameter(
    *         name="per_page",
    *         in="query",
    *         description="Number of items displayed per page",
    *         example=10,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Parameter(
    *         name="sort",
    *         in="query",
    *         description="The field used for sort.",
    *         @OA\Schema(
    *             type="array",
    *             @OA\Items(
    *                 type="string",
    *                 enum = {"id", "create_time"},
    *             )
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="order",
    *         in="query",
    *         description="The method used for sorting.",
    *         @OA\Schema(
    *             type="array",
    *             @OA\Items(
    *                 type="string",
    *                 enum = {"descent", "ascent"},
    *             )
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="status",
    *         in="query",
    *         description="0 = Disabled, 1 = Enabled",
    *         @OA\Schema(
    *             type="integer",
    *             example=1,
    *             @OA\Items(
    *                 type="integer",
    *                 enum = {0, 1},
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Success"
    *     ),
    * )
    */
    public function home()
    {
        $result = $this->admin->paginatedListAPI($this->request->only($this->admin->getAllowHome()), ['groups']);

        return $this->json(...$result);
    }
    /**
    * @OA\Get(
    *     path="/backend/admins/add",
    *     summary="Get the page for adding a new administrator.",
    *     tags={"admins"},
    *     @OA\Response(
    *         response=200,
    *         description="Success"
    *     ),
    * )
    */
    public function add()
    {
        $result = $this->admin->addAPI();

        return $this->json(...$result);
    }
    /**
    * @OA\Post(
    *     path="/backend/admins",
    *     summary="Used for adding a new administrator.",
    *     tags={"admins"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "username",
    *                     "password",
    *                 },
    *                 example={
    *                     "username": "test1000",
    *                     "password": "test1000",
    *                     "display_name": "Test 1000",
    *                     "group": {53},
    *                     "create_time": "2020-11-02T16:11:24+08:00",
    *                     "status": true,
    *                 },
    *                 @OA\Property(
    *                     property="username",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="group",
    *                     type="array",
    *                     @OA\Items(),
    *                 ),
    *                 @OA\Property(
    *                     property="display_name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="create_time",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     type="boolean",
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         description="Success",
    *         response=200,
    *     ),
    * )
    */
    public function save()
    {
        $result = $this->admin->saveAPI($this->request->only($this->admin->getAllowSave()), ['groups']);

        return $this->json(...$result);
    }

    /**
    * @OA\Get(
    *     path="/backend/admins/{id}",
    *     summary="Get information of a specific administrator.",
    *     tags={"admins"},
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(
    *                type="object",
    *                @OA\Property(
    *                    property="success",
    *                    type="boolean",
    *                ),
    *                @OA\Property(
    *                    property="message",
    *                    type="string",
    *                ),
    *                @OA\Property(
    *                    property="data",
    *                    type="object",
    *                    allOf={
    *                       @OA\Schema(ref="#/components/schemas/admin"),
    *                    }
    *                )
    *            )
    *        )
    *     ),
    * )
    */
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
        [ $httpBody ] = $result;
        
        if ($httpBody['success'] === true) {
            $userId = $httpBody['data']['id'];
            $userName = $httpBody['data']['username'];
            Session::set('userId', $userId);
            Session::set('userName', $userName);
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
        if (Session::has('userId')) {
            $data = [
                "name" => Session::get('userName'),
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
            return $this->json($notLogin);
        }
    }
}
