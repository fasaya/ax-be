<?php

namespace App\Http\Resources\V1;

use App\Http\Helpers\ResponseHelper;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($product) use ($request) {
            return $product->toArray($request);
        })->map(function ($data) {
            return new ProductResource($data);
        })->all();
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
            'data' => [
                'data' => $originalData->data,
                'links' => $originalData->links ?? [],
                'meta' => $originalData->meta ?? []
            ]
        ]);
    }
}