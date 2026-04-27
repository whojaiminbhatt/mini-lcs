<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionRequest;
use App\Services\CollectionService;
use App\Traits\Responder;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CollectionController extends Controller
{
    use Responder;

    protected $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    #[OA\Post(path: '/api/collections', description: 'Add a new collection', security: [['sanctum' => []]], responses: [
        new OA\Response(response: 201, description: 'Collection added successfully'),
        new OA\Response(response: 422, description: 'Validation error (e.g. amount exceeds pending)')
    ])]
    public function store(StoreCollectionRequest $request): JsonResponse
    {
        $collection = $this->collectionService->createCollection($request->validated(), $request->user());
        return $this->success($collection, 'Collection added successfully', 201);
    }
}
