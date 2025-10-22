<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies
     */
    public function index(Request $request)
    {
        $query = Company::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by industry
        if ($request->has('industry')) {
            $query->where('industry', $request->industry);
        }

        $companies = $query->orderBy('name')->paginate(15);

        return response()->json($companies);
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'logo_url' => 'nullable|url',
        ]);

        $company = Company::create($request->all());

        return response()->json([
            'message' => 'Company created successfully',
            'company' => $company,
        ], 201);
    }

    /**
     * Display the specified company
     */
    public function show(Company $company)
    {
        $company->load(['jobs', 'applications.user']);
        
        return response()->json($company);
    }

    /**
     * Update the specified company
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'logo_url' => 'nullable|url',
        ]);

        $company->update($request->all());

        return response()->json([
            'message' => 'Company updated successfully',
            'company' => $company,
        ]);
    }

    /**
     * Remove the specified company
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully',
        ]);
    }
}
