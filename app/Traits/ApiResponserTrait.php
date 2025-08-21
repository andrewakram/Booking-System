<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponserTrait
{
    /**
     * Building success response
     * @param $data
     * @param int $code
     * @return ResourceCollection|JsonResponse
     */
    public function successResponse($data = [], int $code = ResponseAlias::HTTP_OK , string $message = null)
    {
//        if ($data instanceof ResourceCollection) {
//            return $data;
//        }

        return response()->json([
            'status'    => $code,
            'message'   => $message ?? "Ok",
            'data'      => $data
        ], $code);
    }

    public function errorResponse($message = null, $code = ResponseAlias::HTTP_CONFLICT)
    {
        return response()->json([
            'status'    => $code,
            'message'   => $message ?? "Error",
            'errors'    => $message,
            'data'      => null
        ], $code);
    }

}
