<?php

    /** @OA\Schema(
    *    schema="admin-list",
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
    *        @OA\Property(
    *            property="page",
    *            type="object",
    *            allOf={
    *               @OA\Schema(
    *                   @OA\Property(
    *                       property="title",
    *                       type="string",
    *                   ),
    *                   @OA\Property(
    *                       property="type",
    *                       type="string",
    *                   ),

    *               ),
    *            },
    *        ),
    *        @OA\Property(
    *            property="layout",
    *            type="object",
    *            allOf={
    *               @OA\Schema(
    *                   @OA\Property(
    *                       property="tableColumn",
    *                       type="object",
    *                   ),
    *                   @OA\Property(
    *                       property="tableToolBar",
    *                       type="object",
    *                   ),
    *                   @OA\Property(
    *                       property="batchToolBar",
    *                       type="object",
    *                   ),
    *               ),
    *            },
    *        ),
    *        @OA\Property(
    *            property="dataSource",
    *            type="array",
    *            @OA\Items(ref="#/components/schemas/admin"),
    *        ),
    *        @OA\Property(
    *            property="meta",
    *            type="object",
    *            allOf={
    *               @OA\Schema(
    *                   @OA\Property(
    *                       property="total",
    *                       type="number",
    *                   ),
    *                   @OA\Property(
    *                       property="per_page",
    *                       type="number",
    *                   ),
    *                   @OA\Property(
    *                       property="page",
    *                       type="number",
    *                   ),
    *               ),
    *            },
    *        ),
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
    *     path="/api/admins",
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
    *        response="200",
    *        description="Success",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/admin-list"),
    *        )
    *     ),
    *     @OA\Response(
    *        response="200.1",
    *        description="Need Login",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/error"),
    *        )
    *     ),
    * )
    */

    /**
    * @OA\Get(
    *     path="/api/admins/add",
    *     summary="Get the page for adding a new administrator.",
    *     tags={"admins"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Response(
    *         response=200,
    *         description="Success"
    *     ),
    * )
    */

    /**
    * @OA\Post(
    *     path="/api/admins",
    *     summary="Used for adding a new administrator.",
    *     tags={"admins"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
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
    *                )
    *            )
    *        )
    *     ),
    * )
    */

    /**
    * @OA\Get(
    *     path="/api/admins/{id}",
    *     summary="Get information of a specific administrator.",
    *     tags={"admins"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */

    /**
    * @OA\Put(
    *     path="/api/admins/{id}",
    *     summary="Update the information of a specific administrator.",
    *     tags={"admins"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "password",
    *                 },
    *                 example={
    *                     "password": "test1000-1",
    *                     "display_name": "Test 1000-1",
    *                     "group": {53},
    *                     "create_time": "2020-11-02T16:11:24+08:00",
    *                     "status": true,
    *                 },
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
    *                )
    *            )
    *        )
    *     ),
    * )
    */

    /**
    * @OA\Post(
    *     path="/api/admins/delete",
    *     summary="Delete administrators.",
    *     tags={"admins"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "type",
    *                     "ids",
    *                 },
    *                 example={
    *                     "type": "delete",
    *                     "ids": {53, 54, 55},
    *                 },
    *                 @OA\Property(
    *                     property="type",
    *                     type="string",
    *                     enum={"delete","deletePermanently"}
    *                 ),
    *                 @OA\Property(
    *                     property="ids",
    *                     type="array",
    *                     @OA\Items(),
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */

    /**
    * @OA\Post(
    *     path="/api/admins/restore",
    *     summary="Restore administrators from the trash can.",
    *     tags={"admins"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "ids",
    *                 },
    *                 example={
    *                     "ids": {53, 54, 55},
    *                 },
    *                 @OA\Property(
    *                     property="ids",
    *                     type="array",
    *                     @OA\Items(),
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */

    /**
    * @OA\Post(
    *     path="/api/admins/login",
    *     summary="Administrator login.",
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
    *                     "username": "admin0",
    *                     "password": "admin0",
    *                 },
    *                 @OA\Property(
    *                     property="username",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string",
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */

    /**
    * @OA\Get(
    *     path="/api/admins/logout",
    *     summary="Administrator log out.",
    *     tags={"admins"},
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */

    /**
    * @OA\Get(
    *     path="/api/admins/info",
    *     summary="Get information of current login user.",
    *     tags={"admins"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */
