<?php
namespace App\Utils;

use Illuminate\Http\JsonResponse;

trait FuncResponse
{
    public function responseData ($data){
        return new JsonResponse([
            'status' => 'success',
            'code'   => 201,
            'data'   => $data,
        ],201);
    }

    public function responseDataCount ($data, $count = null){
        if($count == null){
            return new JsonResponse([
                'status' => 'success',
                'code'   => 200,
                'count'      => count($data),
                'data'      => $data,
            ],200);
        }

        return new JsonResponse([
            'status' => 'success',
            'code'   => 200,
            'count' => $count,
            'data' => $data,
        ], 200);
    }

    public function responseDataLimitOffset($total,$count,$limit,$offset,$data){
        return new JsonResponse([
            'status'    => 'success',
            'code'   => 200,
            'total'    => $total,
            'count'      => $count,
            'limit'      => $limit,
            'offset'      => $offset,
            'data'      => $data,
        ],200);
    }

    public function responseInfo($data){
        return new JsonResponse([
            'status' => 'success',
            'code'   => 200,
            'data' => $data,
        ], 200);
    }

    public function responseValidation($data){
            return new JsonResponse([
                'status'    => 'error',
                'code'   => 422,
                'errors'      => $data,
            ],422);
    }
    public function responseUnauthorized($data){
      return new JsonResponse([
          'status'    => 'error',
          'code'   => 401,
          'errors'      => $data,
      ],401);
    }

    public function responseInternalServerError($info,$detail = ""){
        if ($detail == "") {
            return new JsonResponse([
                'result' => 'false',
                'info' => $info,
                'status'   => 500
            ], 500);
        }else{
            return new JsonResponse([
                'result'    => 'false',
                'info'      => $info,
                'detail'      => $detail,
                'status'   => 500
            ],500);
        }
    }

    public function responseDataNotFound($customMessage = "",$detail = "",$lang =""){
        $statusCode = 400;
        if ($customMessage == "") {
            switch ($lang) {
                case "en" :
                    $info = "Data not found";
                    break;
                default :
                    $info = "Data tidak ditemukan";
            }
        }else{
            $info = $customMessage;
        }
        if ($detail == "") {
            return new JsonResponse([
                'status' => 'error',
                'code'   => $statusCode,
                'data' => $info,
            ], $statusCode);
        } else {
            return new JsonResponse([
                'info' => $info,
                'detail' => $detail,
                'status'   => $statusCode
            ], $statusCode);
        }
    }

    public function limitOffset($count){
        $limit = $count;
        $offset=0;
        if ($count < $limit){
            $limit= $count;
        }
        if(isset($_GET['limit'])){
            $limit =  (int)$_GET['limit'];
        }
        if(isset($_GET['offset'])){
            $offset =  (int)$_GET['offset'];
        }

        return array ($limit,$offset);
    }


    public function errorMessage($info, $code){
        return response($info,$code)->header('Content-Type', 'application/json');
    }

    public function serverApi(){
      $nameServer = $_SERVER['HTTP_HOST'];
      if($nameServer == 'localhost:8000'){
        $serverApi = 'http://localhost:8000/';
      }else{
        $serverApi = 'http://'.env('SERVER_API').'/';
      }

      return $serverApi;
    }
}
