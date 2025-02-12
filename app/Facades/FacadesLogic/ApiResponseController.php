<?php

namespace App\Facades\FacadesLogic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ApiResponseController extends Controller
{
    /**
     * @param $info
     * @param $message
     * @param $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    function apiFormat($info,$message = null,$code= Response::HTTP_OK)
    {
        $response = [
            'code' => $code,
        ];
        if ($message)
            $response['message'] = $message;
        if($info){
            $key = key($info);
            $response[$key] = $info[$key];
        }

        return Response($response,$code);
    }

    public function notFound($message = 'Not Found')
    {
        return $this->apiFormat(
            null,
            $message,
            Response::HTTP_NOT_FOUND);
    }

    public function serverError($message = 'Faild to process this action, please try again.')
    {
        return $this->apiFormat(
            null,
            $message,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }


    public function validationError($errors,$message = 'validation error')
    {
        return $this->faild(
            ['errors' => $errors],
            $message,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function unauthrized($message = 'unauthorized process',$code = Response::HTTP_UNAUTHORIZED)
    {
        return $this->message($message,$code);
    }


    public function faild($errors,$message,$code)
    {
        $errors = $errors ? ['errors' => $errors] : null;
        return $this->apiFormat(
            $errors,
            $message,
            $code
        );
    }

    public function success($data,$message = null,$code = Response::HTTP_OK)
    {
        return $this->apiFormat(
            ['data' => $data],
            $message,$code
        );
    }

    public function message($message,$code = Response::HTTP_OK)
    {
        return $this->apiFormat(
            null,
            $message,
            $code
        );
    }


    public function created($data,$message = 'created successfully')
    {
        return $this->success(
            $data,
            $message,
            Response::HTTP_CREATED
        );
    }

    public function updated($data,$message = 'updated successfully')
    {
        return $this->success(
            $data,
            $message
        );
    }


}
