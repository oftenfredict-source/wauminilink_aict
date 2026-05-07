<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DepartmentApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the departments.
     */
    public function index(): JsonResponse
    {
        $departments = Department::with(['head:id,full_name,phone_number'])
            ->where('status', 'active')
            ->get()
            ->map(function ($dept) {
                return [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'description' => $dept->description,
                    'head_name' => $dept->head->full_name ?? 'N/A',
                    'head_phone' => $dept->head->phone_number ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }

    /**
     * Display the specified department with its members.
     */
    public function show($id): JsonResponse
    {
        $department = Department::with(['head', 'members'])->find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $department->id,
                'name' => $department->name,
                'description' => $department->description,
                'head' => $department->head ? [
                    'id' => $department->head->id,
                    'full_name' => $department->head->full_name,
                    'phone_number' => $department->head->phone_number,
                ] : null,
                'members_count' => $department->members->count(),
                'members' => $department->members->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'full_name' => $member->full_name,
                        'role' => $member->pivot->role ?? 'Member',
                    ];
                }),
            ]
        ]);
    }
}
