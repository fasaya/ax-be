<?php

namespace App\Http\Helpers;

use Request;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendResponse($data = null, $message = "Data successfully fetched", $code = Response::HTTP_OK)
    {
        $response = [
            'status' => 'success',
            'status_code' => $code,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendErrorResponse($message = "Data not found", $code = Response::HTTP_NOT_FOUND, $data = null)
    {
        $response = [
            'status' => 'error',
            'status_code' => $code,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendErrorNotFoundResponse()
    {
        $response = [
            'status' => 'error',
            'status_code' => Response::HTTP_NOT_FOUND,
            'message' => "Data not found",
        ];

        return response()->json($response, Response::HTTP_NOT_FOUND);
    }

    /**
     * return error validation response.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendValidationError($errors)
    {
        return response()->json([
            'status' => 'error',
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => implode(" ", $errors->all()),
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
