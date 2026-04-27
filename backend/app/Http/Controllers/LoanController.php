<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Services\LoanService;
use App\Traits\Responder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class LoanController extends Controller
{
    use Responder;

    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    #[OA\Get(path: '/api/loans', description: 'List all loans', security: [['sanctum' => []]], responses: [
        new OA\Response(response: 200, description: 'Loans retrieved successfully')
    ])]
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['loan_no', 'customer_name']);
        $loans = $this->loanService->getLoans($filters);
        return $this->success($loans, 'Loans retrieved successfully');
    }

    #[OA\Post(path: '/api/loans', description: 'Create or update a loan', security: [['sanctum' => []]], responses: [
        new OA\Response(response: 201, description: 'Loan created successfully'),
        new OA\Response(response: 200, description: 'Loan updated successfully')
    ])]
    public function store(StoreLoanRequest $request): JsonResponse
    {
        $loan = $this->loanService->createLoan($request->validated(), $request->user());
        $message = $loan->wasRecentlyCreated ? 'Loan created successfully' : 'Loan updated successfully';
        return $this->success($loan, $message, $loan->wasRecentlyCreated ? 201 : 200);
    }

    #[OA\Get(path: '/api/loans/{id}', description: 'Get loan details', security: [['sanctum' => []]], parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
    ], responses: [
        new OA\Response(response: 200, description: 'Loan retrieved successfully'),
        new OA\Response(response: 404, description: 'Loan not found')
    ])]
    public function show($id): JsonResponse
    {
        $loan = $this->loanService->getLoanById($id);
        return $this->success($loan, 'Loan retrieved successfully');
    }
}
