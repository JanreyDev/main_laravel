<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    // Store new project
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'particulars' => 'required|string|max:255',
            'year' => 'required|digits:4|integer',
            'budget_allocated' => 'required|numeric|min:0',
        ]);

        // Create project in db_ppdo
        $project = new Project();
        $project->setConnection('pgsql_ppdo'); // use PPDO DB connection
        $project->particulars = $validated['particulars'];
        $project->year = $validated['year'];
        $project->budget_allocated = $validated['budget_allocated'];
        $project->status = 'Ongoing'; // default
        $project->save();

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }
}
