<?php

    /**
    * @OA\OpenApi(
    *     @OA\Info(
    *         version="1.0.1",
    *         title="Public API v2",
    *         description="A free backend data interface. <br/>For development purposes only.<br/>[Authorize] X-API-KEY = antd",
    *         @OA\Contact(
    *             name="Aspirant Zhang",
    *             url="https://www.aspirantzhang.com/antdprov5.html",
    *             email="admin@aspirantzhang.com",
    *         ),
    *     ),

    *     @OA\Server(
    *         description="Online API",
    *         url="https://public-api-v2.aspirantzhang.com/",
    *     ),
    *     @OA\Server(
    *         description="Local API",
    *         url="http://localhost:8000/api/",
    *     ),

    *     @OA\Tag(
    *         name="admins",
    *         description="Operations about administrator",
    *     ),
    *     @OA\Tag(
    *         name="groups",
    *         description="Operations about groups of administrator",
    *     ),
    *     @OA\Tag(
    *         name="rules",
    *         description="Operations about rule",
    *     ),
    *     @OA\Tag(
    *         name="menus",
    *         description="Operations about menu",
    *     ),
    *     @OA\Tag(
    *         name="models",
    *         description="Operations about model",
    *     ),
    * ),

    * @OA\Components(
    *     @OA\SecurityScheme(
    *         type="apiKey",
    *         securityScheme="ApiKeyAuth",
    *         name="X-API-KEY",
    *         in="query"
    *     ),
    * ),
    *
    */

    /** @OA\Schema(
    *    schema="error",
    *    @OA\Property(
    *        property="success",
    *        type="boolean",
    *        default=false
    *    ),
    *    @OA\Property(
    *        property="message",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="data",
    *        type="object",
    *    ),
    * )
    */
