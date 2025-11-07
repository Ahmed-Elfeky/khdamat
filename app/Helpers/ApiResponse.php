<?php

namespace App\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse
{
  public static function SendResponse($status = 200, $message = null, $data = [])
{
    if ($data instanceof JsonResource) {
        $data = $data->response()->getData(true); // getData(true) => array
    }

    $response = [
        'status'  => $status,
        'message' => $message,
        'data'    => $data,
    ];

    return response()->json($response);
}

}
