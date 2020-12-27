<?php

    /** @OA\Schema(
    *    schema="group-list",
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
    *                   @OA\Property(
    *                       property="searchBar",
    *                       type="boolean",
    *                   ),
    *                   @OA\Property(
    *                       property="trash",
    *                       type="boolean",
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
    *            @OA\Items(ref="#/components/schemas/group"),
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
    *    schema="group",
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
    *        property="name",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="rules",
    *        type="array",
    *        @OA\Items()
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
    *     path="/backend/groups",
    *     summary="Get the list of Groups.",
    *     tags={"groups"},
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
    *            @OA\Schema(ref="#/components/schemas/group-list"),
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
    *     path="/backend/groups/add",
    *     summary="Get the page for adding a new group.",
    *     tags={"groups"},
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
    *     path="/backend/groups",
    *     summary="Used for adding a new group.",
    *     tags={"groups"},
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
    *                     "name": "Group 0011",
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
    *     path="/backend/groups/{id}",
    *     summary="Get information of a specific group.",
    *     tags={"groups"},
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
    *     path="/backend/groups/{id}",
    *     summary="Update the information of a specific group.",
    *     tags={"groups"},
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
    *                     "rules",
    *                 },
    *                 example={
    *                     "parent_id": 0,
    *                     "name": "Group 0011",
    *                     "rules": {},
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
    *                     property="rules",
    *                     type="array",
    *                     @OA\Items()
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
    *     path="/backend/groups/delete",
    *     summary="Delete groups.",
    *     tags={"groups"},
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
    *     path="/backend/groups/restore",
    *     summary="Restore groups from the trash can.",
    *     tags={"groups"},
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
