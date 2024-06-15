<?php

namespace App\Http\Resources\V1;

use App\Http\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Customize the outgoing response for the resource collection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        // Get the default collection response
        $originalResponse = parent::toResponse($request);
        $originalData = $originalResponse->getData();

        // Customize the response format
        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'message' => 'Data retrieved successfully',
            'data' => $originalData->data,
        ]);
    }
}
