<?php

/**
 * @OA\Get(summary="获取日志列表",
 *      path="/api/v1/common/LogSearch/getLogList/{type}/{ym}/{d}",
 *      tags={"公共-日志"},
 *      @OA\Parameter(
 *          name="page",
 *          description="页码",
 *          required=true,
 *          in="query",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Parameter(
 *          name="size",
 *          description="每页条数",
 *          required=true,
 *          in="query",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Parameter(
 *          name="type",
 *          description="日志类型",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="string", enum={"info","error","success"})
 *      ),
 *      @OA\Parameter(
 *          name="ym",
 *          description="年月",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="string", example="202008")
 *      ),
 *      @OA\Parameter(
 *          name="d",
 *          description="日",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="string", example="01")
 *      ),
 *      @OA\Response(
 *          response="200",
 *          description="成功",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="code", type="integer", format="int32", description="业务code"),
 *                  @OA\Property(property="data", type="object",
 *                      @OA\Property(property="total", type="integer", description="总条数"),
 *                      @OA\Property(property="rows", type="array",
 *                          @OA\Items(
 *                              @OA\Property(property="time", type="string", description="时间"),
 *                              @OA\Property(property="type", type="string", description="类型", enum={"info","error","success"}),
 *                              @OA\Property(property="msg", type="object"),
 *                          ),
 *                      ),
 *                  ),
 *                  @OA\Property(property="msg", type="string", format="string", description="提示"),
 *                  @OA\Property(property="desc", type="string", format="string", description="详情"),
 *              ),
 *          ),
 *      ),
 *      @OA\Response(
 *          response="400",
 *          description="获取失败",
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