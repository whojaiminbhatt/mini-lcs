<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Traits\Responder;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    use Responder;

    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    #[OA\Get(path: '/api/dashboard', description: 'Get dashboard metrics', responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function metrics(): JsonResponse
    {
        $data = $this->dashboardService->getMetrics();
        return $this->success($data, 'Metrics retrieved successfully');
    }

    #[OA\Get(path: '/api/dashboard/best-time', description: 'Get best collection time prediction', security: [['sanctum' => []]], responses: [
        new OA\Response(response: 200, description: 'Best collection time retrieved successfully')
    ])]
    public function bestCollectionTime(): JsonResponse
    {
        $bestSlot = $this->dashboardService->getBestCollectionTime();
        return $this->success($bestSlot, 'Best collection time retrieved successfully');
    }
}
