<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DataTableDemoController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort column to prevent SQL injection
        $allowedSorts = ['name', 'email', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Filter by role (example filter)
        if ($role = $request->get('role')) {
            // Assuming you have roles setup
            // $query->where('role', $role);
        }

        $users = $query->paginate(10)->withQueryString();

        return view('data-table-demo', compact('users'));
    }
}