<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionTrait
{
    public function apiException($request, $e)
    {
        //Model Not Found Exception
        if ($e instanceof ModelNotFoundException) {

            return $this->modelRes($e);
        } else if ($e instanceof NotFoundHttpException) {

            return $this->httpRes($e);
        } else {

            return parent::render($request, $e);
        }
    }


    // Responses
    public function modelRes($e)
    {
        return response()->json([
            'errors' => 'Record Not Found !!!'
        ], Response::HTTP_NOT_FOUND);
    }

    public function httpRes($e)
    {
        return response()->json([
            'errors' => 'URL or route Not Found !!! '
        ], Response::HTTP_NOT_FOUND);
    }
}
