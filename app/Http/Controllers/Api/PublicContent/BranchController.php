<?php

namespace App\Http\Controllers\Api\PublicContent;

use App\Http\Controllers\Controller;
use App\Services\PublicBranchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(private PublicBranchService $branches)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->branches->branches()->values(),
        ]);
    }

    public function show(string $branchId): JsonResponse
    {
        $branch = $this->branches->find($branchId);

        abort_unless($branch, 404);

        return response()->json([
            'data' => $branch,
        ]);
    }

    public function filter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
        ]);

        $keyword = trim((string) ($validated['keyword'] ?? ''));
        $district = trim((string) ($validated['district'] ?? 'all')) ?: 'all';

        $data = $this->branches->filter($keyword, $district);

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
        ]);
    }

}
