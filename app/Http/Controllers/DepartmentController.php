<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin() && !auth()->user()->isPastor() && !auth()->user()->isSecretary()) {
                abort(403, 'Unauthorized. Only Administrators, Pastors, and Secretaries can manage departments.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the departments.
     */
    public function index()
    {
        $departments = Department::with(['head', 'members'])->orderBy('name')->get();
        $members = Member::orderBy('full_name')->get();

        return view('departments.index', compact('departments', 'members'));
    }

    /**
     * Assign members to a department.
     */
    public function assignMembers(Request $request, Department $department)
    {
        $request->validate([
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:members,id',
        ]);

        $memberIds = $request->input('member_ids', []);
        $department->members()->sync($memberIds);

        $count = count($memberIds);
        return redirect()->route('departments.index')
            ->with('success', "Members updated successfully. {$count} member(s) assigned to {$department->name}.");
    }

    /**
     * Remove a single member from a department.
     */
    public function removeMember(Department $department, Member $member)
    {
        $department->members()->detach($member->id);

        return redirect()->route('departments.index')
            ->with('success', "{$member->full_name} removed from {$department->name}.");
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:members,id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to create department. Please check the errors below.');
        }

        Department::create($request->all());

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:members,id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to update department. Please check the errors below.');
        }

        $department->update($request->all());

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
