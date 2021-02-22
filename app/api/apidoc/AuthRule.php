<?php

    /** @OA\Schema(
    *    schema="rule-list",
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
    *            @OA\Items(ref="#/components/schemas/rule"),
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
    *    schema="rule",
    *    @OA\Property(
    *        property="id",
    *        type="integer",
    *        format="int32",
    *    ),
    *    @OA\Property(
    *        property="parent_id",
    *        type="integer",
    *        format="int32",
    *    ),
    *    @OA\Property(
    *        property="rule",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="name",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="type",
    *        type="integer",
    *    ),
    *    @OA\Property(
    *        property="condition",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="create_time",
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
    *     path="/api/rules",
    *     summary="Get the list of rules.",
    *     tags={"rules"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
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
    *                 enum = {"desc", "asc"},
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
    *            @OA\Schema(ref="#/components/schemas/rule-list"),
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
    *     path="/api/rules/add",
    *     summary="Get the page for adding a new rule.",
    *     tags={"rules"},
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
    *     path="/api/rules",
    *     summary="Used for adding a new rule.",
    *     tags={"rules"},
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
    *                     "parent_id",
    *                     "name",
    *                 },
    *                 example={
    *                     "parent_id": 0,
    *                     "name": "User Test",
    *                     "rule": "api/user/test",
    *                     "create_time": "2020-11-02T16:11:24+08:00",
    *                     "status": true,
    *                 },
    *                 @OA\Property(
    *                     property="parent_id",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="rule",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="type",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="condition",
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
    *     path="/api/rules/{id}",
    *     summary="Get information of a specific rule.",
    *     tags={"rules"},
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
    *     path="/api/rules/{id}",
    *     summary="Update the information of a specific rule.",
    *     tags={"rules"},
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
    *                     "parent_id",
    *                     "name",
    *                 },
    *                 example={
    *                     "parent_id": 0,
    *                     "name": "User Test",
    *                     "rule": "api/user/test",
    *                     "create_time": "2020-11-02T16:11:24+08:00",
    *                     "status": true,
    *                 },
    *                 @OA\Property(
    *                     property="parent_id",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="rule",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="type",
    *                     type="integer",
    *                 ),
    *                 @OA\Property(
    *                     property="condition",
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
    *     path="/api/rules/delete",
    *     summary="Delete rules.",
    *     tags={"rules"},
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
    *     path="/api/rules/restore",
    *     summary="Restore rules from the trash can.",
    *     tags={"rules"},
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
