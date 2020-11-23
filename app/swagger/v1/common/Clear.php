<?php

/**
 * @OA\Get(summary="清除缓存",
 *      path="/api/v1/common/Clear/cache/{name}",
 *      tags={"公共-清理"},
 *      @OA\Parameter(
 *          name="name",
 *          description="缓存名",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="string")
 *      ),
 *      @OA\Response(
 *          response="200",
 *          description="清除成功",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="code", type="integer", format="int32", description="业务code"),
 *                  @OA\Property(property="msg", type="string", format="string", description="提示"),
 *                  @OA\Property(property="desc", type="string", format="string", description="详情"),
 *              ),
 *          ),
 *      ),
 *      @OA\Response(
 *          response="400",
 *          description="清除失败",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="code", type="integer", format="int32", description="业务code", example="500"),
 *                  @OA\Property(property="msg", type="string", format="string", description="错误提示"),
 *                  @OA\Property(property="desc", type="string", format="string", description="错误详情")
 *              ),
 *          ),
 *      ),
 *  )
 */