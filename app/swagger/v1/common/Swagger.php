<?php
/**
 * @OA\Swagger(
 * schemes={"http"},
 * host="http://xh.wlqcym.cn",
 * basePath="/",
 * @OA\Info(
 * version="1.0.0",
 * title="百分微信服务 API",
 * description="Version: 1.0.0",
 * @OA\Contact(name = "sugm", email = "")
 * ),
 */

/**
 * @OA\Get(
 *     path="/api/v1/common/Swagger/create",
 *     tags={"更新swagger"},
 *     summary="更新swagger",
 *     @OA\Response(
 *         response="200",
 *         description="成功",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="code", type="integer", format="int32", description="业务code"),
 *                 @OA\Property(property="msg", type="string", format="string", description="提示"),
 *                 @OA\Property(property="desc", type="string", format="string", description="详情"),
 *                 example={"code": 200, "msg": "ok", "desc": ""}
 *             ),
 *         ),
 *     )
 * )
 */